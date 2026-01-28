<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\TelegramService;

class QueueController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Patient Checks In to get a Queue Number.
     */
    public function checkIn(Appointment $appointment)
    {
        \Illuminate\Support\Facades\Log::info("QueueController checkIn called for Appointment ID: {$appointment->id}");

        if ($appointment->user_id !== Auth::id()) {
            \Illuminate\Support\Facades\Log::warning("QueueController: User mismatch");
            abort(403);
        }

        // Validate: Must be today, must be paid, must not already have a queue number
        if ($appointment->appointment_date !== Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d')) {
            \Illuminate\Support\Facades\Log::info("QueueController: Wrong date");
            return back()->with('error', 'You can only check in on the appointment day.');
        }

        if ($appointment->status !== 'paid') {
             \Illuminate\Support\Facades\Log::info("QueueController: Not paid");
            return back()->with('error', 'Please pay for your appointment before checking in.');
        }

        if ($appointment->queue_number) {
             \Illuminate\Support\Facades\Log::info("QueueController: Already checked in");
            return back()->with('info', 'You are already checked in. Queue #' . $appointment->queue_number);
        }

        // Assign Queue Number
        // Logic: Max queue number for today + 1 FOR THIS DOCTOR
        $maxQueue = Appointment::whereDate('appointment_date', Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d'))
            ->where('doctor_id', $appointment->doctor_id)
            ->max('queue_number') ?? 0;

        $appointment->update([
            'queue_number' => $maxQueue + 1,
            'queue_status' => 'waiting',
            'checked_in_at' => now(),
        ]);
        
        \Illuminate\Support\Facades\Log::info("Queue assigned: " . ($maxQueue + 1));

        // Send Telegram Notification
        try {
            $this->telegramService->sendQueueAssigned(Auth::user(), $appointment);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Telegram Queue Notification Failed: " . $e->getMessage());
        }

        return back()->with('success', 'Check-in successful! Your Queue Number is ' . $appointment->queue_number);
    }

    /**
     * API for Real-Time Patient Queue Status.
     */
    public function getPatientQueueStatus()
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d');

        // Find active appointment for today
        $appointment = Appointment::where('user_id', $user->id)
            ->whereDate('appointment_date', $today)
            ->whereNotNull('queue_number')
            ->whereIn('queue_status', ['waiting', 'called', 'arrived'])
            ->first();

        if (!$appointment) {
            return response()->json(['active' => false]);
        }

        // Calculate position
        // Count how many are 'waiting' or 'called' or 'consulting' with queue_number < my queue_number
        // Actually, position is simply: (Rank among waiting)
        $currentServing = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->whereIn('queue_status', ['called', 'arrived', 'consulting'])
            ->orderBy('appointment_time', 'asc')
            ->first();
            
        $peopleAhead = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('queue_status', 'waiting')
            ->where('appointment_time', '<', $appointment->appointment_time) // Rank by time
            ->count();

        // If there is someone currently being served, add 1 to effective people ahead logic if we want "people before me"
        // But user asked for "Current Position". 
        // Let's define "Current Position" as "My place in line". 
        // If I am next, position is 1. 
        $position = $peopleAhead + 1;

        // Estimate wait time based on reason complexity
        // Default: 15 mins. 
        // Heuristic: Longer reason = potentially more complex = +5-10 mins.
        $estimatedWait = 0;
        
        // We need to sum up estimated time for all people ahead
        $peopleAheadAppointments = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('queue_status', 'waiting')
            ->where('appointment_time', '<', $appointment->appointment_time)
            ->get();

        foreach($peopleAheadAppointments as $apt) {
            $baseTime = 15;
            $reasonLen = strlen($apt->reason ?? '');
            if ($reasonLen > 100) $baseTime += 10;
            elseif ($reasonLen > 50) $baseTime += 5;
            
            $estimatedWait += $baseTime;
        }
        
        // Add time for current serving if any (assume half done? or full duration)
        if ($currentServing) {
             $baseTime = 15;
             $reasonLen = strlen($currentServing->reason ?? '');
             if ($reasonLen > 100) $baseTime += 10;
             elseif ($reasonLen > 50) $baseTime += 5;
             $estimatedWait += $baseTime;
        }
        
        // Calculate wait time since check-in
        // Logic: Max(Queue Processing Time, Time Until Schedule)
        
        // 1. Queue Processing Time
        $queueWait = $estimatedWait; // Already calculated above
        
        // 2. Schedule Wait Time
        $apptTime = Carbon::parse($today . ' ' . $appointment->appointment_time);
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $scheduleWait = $now->diffInMinutes($apptTime, false);
        if ($scheduleWait < 0) $scheduleWait = 0;
        
        // Final Estimated Wait
        $estimatedWait = max($queueWait, $scheduleWait);

        return response()->json([
            'active' => true,
            'queue_number' => $appointment->queue_number,
            'current_position' => $position,
            'estimated_wait' => $estimatedWait,
            'status' => ucfirst($appointment->queue_status),
            'current_serving' => $currentServing ? $currentServing->queue_number : '-'
        ]);
    }

    /**
     * Doctor: Get Current Queue State (for Dashboard).
     */
    public function getDoctorQueueState()
    {
        $data = $this->getQueueData(Auth::id());
        return response()->json($data)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    /**
     * SSE Stream for Doctor Queue
     */
    public function streamQueue(Request $request)
    {
        // Ensure user is authenticated and is a doctor
        if (!Auth::check() || Auth::user()->role !== 'doctor') {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        
        return response()->stream(function () use ($user) {
            // Close session to prevent locking
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            $oldHash = null;
            $startTime = time();
            
            // 2 minutes max execution
            while (true) {
                if (connection_aborted() || (time() - $startTime > 120)) {
                    break;
                }

                $data = $this->getQueueData($user->id);

                $currentHash = md5(json_encode($data));

                if ($currentHash !== $oldHash) {
                    echo "data: " . json_encode($data) . "\n\n";
                    ob_flush();
                    flush();
                    $oldHash = $currentHash;
                }

                // Heartbeat
                echo ": keepalive\n\n";
                ob_flush();
                flush();

                sleep(3);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function getQueueData($doctorId)
    {
        // Enforce Timezone
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $today = Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d');
        
        $currentServing = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $doctorId)
            ->whereIn('queue_status', ['called', 'arrived', 'consulting'])
            ->orderBy('appointment_time', 'asc')
            ->first();

        // Real-Time Queue Logic
        $queueList = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $doctorId)
            ->where(function($q) {
                $q->whereIn('status', ['paid', 'booked', 'Paid', 'Booked']);
            })
            ->where(function ($query) {
                $query->whereNotNull('queue_number') 
                      ->orWhereNull('queue_number');
            })
            ->where(function ($query) {
                $query->whereIn('queue_status', ['waiting', 'called', 'arrived', 'consulting', 'skipped', 'Waiting', 'Called', 'Consulting', 'Skipped'])
                      ->orWhereNull('queue_status');
            })
            ->orderBy('appointment_time', 'asc')
            ->with('patient')
            ->get();
            
        $queueListMapped = $queueList->map(function ($apt) {
            return [
                'id' => $apt->id,
                'queue_number' => $apt->queue_number ?? '-',
                'patient_name' => optional($apt->patient)->name ?? 'Unknown',
                'reason' => $apt->reason,
                'time' => Carbon::parse($apt->appointment_time)->format('H:i'),
                'status' => ucfirst($apt->queue_status ?? 'Waiting'), 
                'wait_time' => $apt->checked_in_at ? Carbon::parse($apt->checked_in_at)->diffInMinutes(now()) . ' min' : '-',
            ];
        });

        $waitingCount = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['paid', 'booked', 'Paid', 'Booked'])
            ->where(function ($q) {
                $q->where('queue_status', 'waiting')
                  ->orWhereNull('queue_status');
            })
            ->count();

        return [
            'current_serving' => $currentServing ? $currentServing->queue_number : '-',
            'current_serving_name' => $currentServing ? ($currentServing->patient_name ?? optional($currentServing->patient)->name ?? 'Unknown') : '-',
            'waiting_count' => $waitingCount,
            'queue_list' => $queueListMapped
        ];
    }

    /**
     * Doctor Actions: Call Next, Complete, Skip.
     */
    public function updateQueueStatus(Request $request, Appointment $appointment)
    {
        // Validation handled by route/middleware usually, but good to check role
        // Assuming this route is protected by auth and maybe doctor middleware
        
        $action = $request->input('action'); // call, complete, skip, consulting

        if ($action === 'call') {
            $appointment->update(['queue_status' => 'called']);
            // Notify Patient
            try {
                $this->telegramService->sendDoctorCalling($appointment->patient, $appointment);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Telegram Error (Call): " . $e->getMessage());
            }
        } elseif ($action === 'consulting') {
            $appointment->update(['queue_status' => 'consulting']);
        } elseif ($action === 'complete') {
            $appointment->update(['status' => 'completed', 'queue_status' => 'completed']);
            
            // Send Follow-up
            try {
                $this->telegramService->sendFollowUp($appointment->patient, $appointment);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Telegram Error (FollowUp): " . $e->getMessage());
            }
            
        } elseif ($action === 'skip') {
            $appointment->update(['queue_status' => 'skipped']);
            
            // Notify Patient
            try {
                $this->telegramService->sendSkippedNotification($appointment->patient, $appointment);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Telegram Error (Skipped): " . $e->getMessage());
            }
        }
        
        // Notify others of queue update
        try {
            $this->notifyQueueUpdates($appointment->doctor_id);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Queue Update Notification Error: " . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }
    
    /**
     * Doctor: Call Next Available Patient
     */
    public function callNext()
    {
        $today = Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d');

        // Find next waiting patient (including those null queue status but valid appointment)
        $next = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', Auth::id())
            ->whereIn('status', ['paid', 'booked'])
            ->where(function ($q) {
                $q->where('queue_status', 'waiting')
                  ->orWhereNull('queue_status');
            })
            ->orderBy('appointment_time', 'asc')
            ->first();
            
        if ($next) {
            // Auto-assign queue number if missing
            if (!$next->queue_number) {
                 $maxQueue = Appointment::whereDate('appointment_date', $today)
                    ->where('doctor_id', Auth::id())
                    ->max('queue_number') ?? 0;
                 $next->queue_number = $maxQueue + 1;
            }

            $next->update([
                'queue_status' => 'called',
                'queue_number' => $next->queue_number // Save it if it was generated
            ]);
            // Notify Patient
            try {
                $this->telegramService->sendDoctorCalling($next->patient, $next);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Telegram Error (CallNext): " . $e->getMessage());
            }
            
            // Notify others of queue update
            try {
                $this->notifyQueueUpdates(Auth::id());
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Queue Update Notification Error: " . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Calling Queue #' . $next->queue_number]);
        }
        
        return response()->json(['success' => false, 'message' => 'No patients waiting.']);
    }

    /**
     * Notify Delay
     */
    public function notifyDelay(Request $request)
    {
        $minutes = $request->input('minutes');
        $today = Carbon::today()->format('Y-m-d');

        // Notify all waiting patients for today
        $appointments = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', Auth::id())
            ->where('queue_status', 'waiting')
            ->get();

        foreach ($appointments as $appointment) {
            try {
                $this->telegramService->sendDelayNotification($appointment->patient, $appointment, $minutes);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Telegram Error (Delay): " . $e->getMessage());
            }
        }

        return response()->json(['success' => true, 'message' => 'Delay notifications sent.']);
    }

    protected function notifyQueueUpdates($doctorId = null)
    {
        $today = Carbon::today()->format('Y-m-d');
        $doctorId = $doctorId ?? Auth::id(); // Fallback to auth if not provided
        
        $waitingAppointments = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $doctorId)
            ->where('queue_status', 'waiting')
            ->orderBy('queue_number', 'asc')
            ->get();
            
        foreach ($waitingAppointments as $index => $appointment) {
            $position = $index + 1;
            $estimatedWait = $position * 15; // Assumption: 15 mins per patient
            
            try {
                $this->telegramService->sendQueuePositionUpdate($appointment->patient, $appointment, $position, $estimatedWait);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Telegram Error (Update): " . $e->getMessage());
            }
        }
    }
}
