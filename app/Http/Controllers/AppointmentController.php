<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\DoctorLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Services\TelegramService;

class AppointmentController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function index()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        $appointments = Appointment::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($today, $currentTime) {
                $query->where('appointment_date', '>', $today)
                      ->orWhere(function($q) use ($today, $currentTime) {
                          $q->where('appointment_date', '=', $today)
                            ->where('appointment_time', '>', $currentTime);
                      });
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('patient.appointments.index', compact('appointments'));
    }

    public function history()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        $appointments = Appointment::where('user_id', Auth::id())
            ->where(function($query) use ($today, $currentTime) {
                $query->where('status', 'cancelled')
                      ->orWhere('appointment_date', '<', $today)
                      ->orWhere(function($q) use ($today, $currentTime) {
                          $q->where('appointment_date', '=', $today)
                            ->where('appointment_time', '<=', $currentTime);
                      });
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        return view('patient.appointments.history', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'doctor_id' => 'nullable|exists:users,id'
        ]);

        $slots = $this->calculateSlots($request->date, $request->doctor_id);
        return response()->json($slots);
    }

    public function calculateSlots($date, $doctorId)
    {
        Log::info("calculateSlots called for Date: $date, Doctor: $doctorId");
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        Log::info("DayOfWeek: $dayOfWeek");
        
        $interval = 15; // minutes
        $slots = [];

        // Determine effective start/end times and available doctors
        $availableDoctors = [];
        if ($doctorId) {
            $doctor = User::find($doctorId);
            
            // Check full day leave
            $onFullLeave = $doctor->leaves()
                ->where('status', 'approved')
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->whereNull('start_time')
                ->whereNull('end_time')
                ->exists();
            
            if ($onFullLeave) {
                return []; // No slots if on full leave
            }

            // Check schedule
            $schedule = $doctor->schedules()->where('day_of_week', $dayOfWeek)->first();
            
            if ($schedule) {
                Log::info("Schedule found. Active: " . $schedule->is_active);
                if (!$schedule->is_active) {
                    Log::info("Schedule inactive. Returning empty.");
                    return []; // Doctor is OFF on this recurring day
                }
                
                // Ensure times are set
                if (!$schedule->start_time || !$schedule->end_time) {
                    return [];
                }

                $start = Carbon::parse($date . ' ' . $schedule->start_time);
                $end = Carbon::parse($date . ' ' . $schedule->end_time);
            } else {
                 Log::info("No schedule found. Defaulting to 8-10pm.");
                 // Default to standard clinic hours if no specific schedule set
                 $start = Carbon::parse($date . ' 08:00:00');
                 $end = Carbon::parse($date . ' 22:00:00');
            }
            
            // Get partial leaves
            $partialLeaves = $doctor->leaves()
                ->where('status', 'approved')
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();

            $availableDoctors[] = ['id' => $doctor->id, 'start' => $start, 'end' => $end, 'partial_leaves' => $partialLeaves];

        } else {
            // "Any Doctor" logic
            // Get all doctors NOT on leave
            $doctors = User::where('role', 'doctor')->get();
            foreach ($doctors as $doc) {
                 $onFullLeave = $doc->leaves()
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->whereNull('start_time')
                    ->whereNull('end_time')
                    ->exists();
                 if ($onFullLeave) continue;

                 // Check Schedule
                 $schedule = $doc->schedules()->where('day_of_week', $dayOfWeek)->first();
                 
                 if ($schedule) {
                     if (!$schedule->is_active) {
                         continue; // Doctor is OFF
                     }
                     $start = Carbon::parse($date . ' ' . $schedule->start_time);
                     $end = Carbon::parse($date . ' ' . $schedule->end_time);
                 } else {
                     $start = Carbon::parse($date . ' 08:00:00');
                     $end = Carbon::parse($date . ' 22:00:00');
                 }
                 
                 // Get partial leaves
                 $partialLeaves = $doc->leaves()
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->get();

                 $availableDoctors[] = ['id' => $doc->id, 'start' => $start, 'end' => $end, 'partial_leaves' => $partialLeaves];
            }
        }

        if (empty($availableDoctors)) {
             return [];
        }

        $minStart = null;
        $maxEnd = null;

        foreach ($availableDoctors as $docData) {
            if ($minStart === null || $docData['start']->lt($minStart)) $minStart = $docData['start'];
            if ($maxEnd === null || $docData['end']->gt($maxEnd)) $maxEnd = $docData['end'];
        }
        
        $currentTime = $minStart->copy();
        $now = Carbon::now();
        $isToday = Carbon::parse($date)->isToday();

        while ($currentTime->lt($maxEnd)) {
             $timeString = $currentTime->format('H:i:s');
             $displayTime = $currentTime->format('h:i A');

             // Real-time check
             if ($isToday && $currentTime->lte($now)) {
                 $currentTime->addMinutes($interval);
                 continue;
             }

             // Check if AT LEAST ONE doctor from availableDoctors is free at this time
             $isSlotAvailable = false;

             foreach ($availableDoctors as $docData) {
                 // Check if slot is within doctor's working hours
                 if ($currentTime->lt($docData['start']) || $currentTime->gte($docData['end'])) {
                     continue;
                 }
                 
                 // Check partial leaves
                 $isPartialLeave = false;
                 foreach($docData['partial_leaves'] as $leave) {
                      $leaveStart = Carbon::parse($date . ' ' . $leave->start_time);
                      $leaveEnd = Carbon::parse($date . ' ' . $leave->end_time);
                      
                      // Check if current slot falls within partial leave
                      if ($currentTime->gte($leaveStart) && $currentTime->lt($leaveEnd)) {
                          $isPartialLeave = true;
                          break;
                      }
                 }
                 if ($isPartialLeave) continue;

                 // Check bookings
                 $isBooked = Appointment::where('doctor_id', $docData['id'])
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $timeString)
                    ->whereIn('status', ['booked', 'paid', 'pending_payment'])
                    ->exists();
                 
                 if (!$isBooked) {
                     $isSlotAvailable = true;
                     break; // Found a doctor for this slot
                 }
             }

             if ($isSlotAvailable) {
                 $slots[] = [
                     'time' => $timeString,
                     'display' => $displayTime
                 ];
             }

             $currentTime->addMinutes($interval);
        }
        
        return $slots;
    }

    public function preview(Request $request)
    {
        Log::info('AppointmentController@preview called', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'ic_number' => 'required|string|max:20',
                'date' => 'required|date',
                'time' => 'required',
                'doctor_id' => 'nullable|exists:users,id',
                'weight' => 'nullable|numeric',
                'type' => 'nullable|string',
                'reason' => 'nullable|string',
            ]);

            $data = $request->all();
            $doctor = $request->doctor_id ? User::find($request->doctor_id) : null;
            
            return view('patient.appointments.summary', compact('data', 'doctor'));
        } catch (\Throwable $e) {
            Log::error('Error in AppointmentController@preview: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'An error occurred while generating the preview: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|max:20',
            'date' => 'required|date',
            'time' => 'required',
            'doctor_id' => 'nullable|exists:users,id',
            'weight' => 'nullable|numeric',
            'type' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        $date = $request->date;
        $time = $request->time;
        $doctorId = $request->doctor_id;
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $timeCarbon = Carbon::parse($date . ' ' . $time);

        $finalDoctorId = null;

        if ($doctorId) {
            // Specific Doctor Selected
            $doctor = User::find($doctorId);
            
            // 1. Check Full Day Leave
            $onFullLeave = $doctor->leaves()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->whereNull('start_time')
                ->whereNull('end_time')
                ->exists();
            if ($onFullLeave) {
                return back()->withErrors(['date' => 'Doctor is on leave on this date.'])->withInput();
            }

            // 2. Check Partial Leave (Unavailable Slots)
            $partialLeaves = $doctor->leaves()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->get();
            
            foreach($partialLeaves as $leave) {
                $leaveStart = Carbon::parse($date . ' ' . $leave->start_time);
                $leaveEnd = Carbon::parse($date . ' ' . $leave->end_time);
                if ($timeCarbon->gte($leaveStart) && $timeCarbon->lt($leaveEnd)) {
                     return back()->withErrors(['time' => 'Doctor is unavailable at this time slot.'])->withInput();
                }
            }

            // 3. Check Schedule
            $schedule = $doctor->schedules()->where('day_of_week', $dayOfWeek)->first();
            
            if ($schedule) {
                if (!$schedule->is_active) {
                     return back()->withErrors(['date' => 'Doctor is not working on this day.'])->withInput();
                }
                $start = Carbon::parse($date . ' ' . $schedule->start_time);
                $end = Carbon::parse($date . ' ' . $schedule->end_time);
            } else {
                $start = Carbon::parse($date . ' 08:00:00');
                $end = Carbon::parse($date . ' 22:00:00');
            }

            if ($timeCarbon->lt($start) || $timeCarbon->gte($end)) {
                 return back()->withErrors(['time' => 'Selected time is outside doctor working hours.'])->withInput();
            }

            // 4. Atomic Booking with Lock
            $lockKey = "booking_{$doctorId}_{$date}_{$time}";
            $lock = \DB::select("SELECT GET_LOCK(?, 5) as locked", [$lockKey]); // Wait up to 5s
            
            if (!$lock[0]->locked) {
                return back()->withErrors(['time' => 'System busy. Please try again.'])->withInput();
            }

            try {
                // Check Booking again inside lock
                $exists = Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $time)
                    ->where('status', '!=', 'cancelled')
                    ->exists();
                
                if ($exists) {
                    return back()->withErrors(['time' => 'This slot has just been booked. Please choose another.'])->withInput();
                }
                
                $finalDoctorId = $doctorId;

                $appointment = Appointment::create([
                    'user_id' => Auth::id(),
                    'patient_name' => $request->name,
                    'patient_ic' => $request->ic_number,
                    'doctor_id' => $doctorId,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'weight' => $request->weight,
                    'type' => $request->type,
                    'reason' => $request->reason,
                    'status' => 'pending_payment'
                ]);

            } finally {
                \DB::select("SELECT RELEASE_LOCK(?)", [$lockKey]);
            }

        } else {
            // Any Available Doctor
            $doctors = User::where('role', 'doctor')->get();
            $booked = false;

            foreach ($doctors as $doc) {
                 // Check Full Day Leave
                 $onFullLeave = $doc->leaves()
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->whereNull('start_time')
                    ->whereNull('end_time')
                    ->exists();
                 if ($onFullLeave) continue;

                 // Check Partial Leave
                 $partialLeaves = $doc->leaves()
                    ->where('status', 'approved')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->whereNotNull('start_time')
                    ->whereNotNull('end_time')
                    ->get();
                 
                 $isUnavailable = false;
                 foreach($partialLeaves as $leave) {
                    $leaveStart = Carbon::parse($date . ' ' . $leave->start_time);
                    $leaveEnd = Carbon::parse($date . ' ' . $leave->end_time);
                    if ($timeCarbon->gte($leaveStart) && $timeCarbon->lt($leaveEnd)) {
                         $isUnavailable = true;
                         break;
                    }
                 }
                 if ($isUnavailable) continue;

                 // Check Schedule
                 $schedule = $doc->schedules()->where('day_of_week', $dayOfWeek)->first();
                 
                 if ($schedule) {
                     if (!$schedule->is_active) continue; // Doctor is OFF
                     
                     $start = Carbon::parse($date . ' ' . $schedule->start_time);
                     $end = Carbon::parse($date . ' ' . $schedule->end_time);
                 } else {
                     $start = Carbon::parse($date . ' 08:00:00');
                     $end = Carbon::parse($date . ' 22:00:00');
                 }

                 if ($timeCarbon->lt($start) || $timeCarbon->gte($end)) continue;

                 // Try to lock and book
                 $lockKey = "booking_{$doc->id}_{$date}_{$time}";
                 $lock = \DB::select("SELECT GET_LOCK(?, 0) as locked", [$lockKey]); // No wait, just try next doctor
                 
                 if (!$lock[0]->locked) continue;

                 try {
                     $exists = Appointment::where('doctor_id', $doc->id)
                        ->where('appointment_date', $date)
                        ->where('appointment_time', $time)
                        ->where('status', '!=', 'cancelled')
                        ->exists();
                     
                     if (!$exists) {
                         $finalDoctorId = $doc->id;
                         $appointment = Appointment::create([
                           'user_id' => Auth::id(),
                           'patient_name' => $request->name,
                           'patient_ic' => $request->ic_number,
                           'doctor_id' => $finalDoctorId,
                           'appointment_date' => $date,
                            'appointment_time' => $time,
                            'weight' => $request->weight,
                            'type' => $request->type,
                            'reason' => $request->reason,
                            'status' => 'pending_payment'
                        ]);
                        $booked = true;
                        break; // Stop looking, we booked one
                     }
                 } finally {
                     \DB::select("SELECT RELEASE_LOCK(?)", [$lockKey]);
                 }
            }

            if (!$booked) {
                return back()->withErrors(['time' => 'No doctors available at this time anymore.'])->withInput();
            }
        }

        return redirect()->route('patient.appointments.payment', $appointment->id);
    }

    public function showPayment(Appointment $appointment)
    {
        Log::info('showPayment entered for appointment: ' . $appointment->id);
        
        if ($appointment->user_id !== Auth::id()) {
            Log::warning('showPayment: User mismatch');
            abort(403);
        }
        
        if ($appointment->status === 'paid') {
            Log::info('showPayment: Already paid, redirecting to receipt');
            return redirect()->route('patient.appointments.receipt', $appointment->id);
        }

        Log::info('showPayment: Showing simulation view');
        return view('patient.payment.simulation', compact('appointment'));
    }

    public function processPayment(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->payment()->create([
            'receipt_number' => 'REC-' . strtoupper(uniqid()),
            'amount' => 2.00,
            'payment_date' => now(),
            'status' => 'paid',
        ]);

        $appointment->update(['status' => 'paid']);

        // Send Telegram Notification
        $sent = $this->telegramService->sendPaymentConfirmation(Auth::user(), $appointment);
        
        if (!$sent) {
             session()->flash('warning', 'Payment successful, but we could not send the Telegram confirmation. Please ensure your Telegram account is linked in your profile.');
        }

        // Auto-Assign Queue if appointment is TODAY
        if ($appointment->appointment_date == Carbon::today()->format('Y-m-d')) {
            $maxQueue = Appointment::whereDate('appointment_date', Carbon::today()->format('Y-m-d'))
                ->max('queue_number') ?? 0;

            if (!$appointment->queue_number) {
                $appointment->update([
                    'queue_number' => $maxQueue + 1,
                    'queue_status' => 'waiting',
                    'checked_in_at' => now(),
                ]);
                
                $this->telegramService->sendQueueAssigned($appointment->patient, $appointment);
            }
        }

        return redirect()->route('patient.appointments.receipt', $appointment->id);
    }

    public function showReceipt(Appointment $appointment)
    {
         if ($appointment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $payment = $appointment->payment;
        if (!$payment) {
            return redirect()->route('patient.appointments.payment', $appointment->id);
        }

        return view('patient.payment.receipt', compact('appointment', 'payment'));
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->update([
            'cancellation_status' => 'pending'
        ]);

        return back()->with('success', 'Cancellation request sent to doctor.');
    }

    public function showRescheduleForm(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }
        
        $doctors = User::where('role', 'doctor')->get();
        return view('patient.appointments.reschedule', compact('appointment', 'doctors'));
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'doctor_id' => 'nullable|exists:users,id',
            'weight' => 'nullable|numeric',
            'type' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        $rescheduleData = [
            'date' => $request->date,
            'time' => $request->time,
            'doctor_id' => $request->doctor_id,
            'weight' => $request->weight,
            'type' => $request->type,
            'reason' => $request->reason,
        ];

        $appointment->update([
            'reschedule_status' => 'pending',
            'reschedule_data' => $rescheduleData
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Reschedule request sent to doctor.');
    }
}
