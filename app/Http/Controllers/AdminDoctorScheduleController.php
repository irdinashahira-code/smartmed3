<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\DoctorLeave;
use App\Models\User;
use Carbon\Carbon;

class AdminDoctorScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index($doctorId)
    {
        $doctor = User::findOrFail($doctorId);
        if ($doctor->role !== 'doctor') {
            abort(404, 'User is not a doctor.');
        }

        $schedules = $doctor->schedules()->orderBy('day_of_week')->get();
        // Order by status (pending first) then start date
        $leaves = $doctor->leaves()
            ->where('end_date', '>=', now()->toDateString())
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('start_date')
            ->get();

        $weekDays = [
            0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
            4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday',
        ];

        return view('admin.doctors.schedule', compact('doctor', 'schedules', 'leaves', 'weekDays'));
    }

    public function updateSchedule(Request $request, $doctorId)
    {
        $doctor = User::findOrFail($doctorId);
        
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_active' => 'boolean',
        ]);

        // Conflict Check logic (Same as DoctorScheduleController)
        foreach ($request->schedules as $data) {
            $dayOfWeek = $data['day_of_week'];
            $isActive = isset($data['is_active']) ? $data['is_active'] : false;
            
            $futureAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', '>=', now()->toDateString())
                ->where('status', '!=', 'cancelled')
                ->whereRaw("DAYOFWEEK(appointment_date) = ?", [$dayOfWeek + 1])
                ->get();

            if ($futureAppointments->isEmpty()) {
                continue;
            }

            if (!$isActive) {
                return back()->with('error', "Cannot disable " . $this->getDayName($dayOfWeek) . " as doctor has existing future appointments on this day.");
            } else {
                $newStart = $data['start_time'] ?? '08:00';
                $newEnd = $data['end_time'] ?? '22:00';

                foreach ($futureAppointments as $apt) {
                    $aptTime = $apt->appointment_time;
                    $aptCarbon = Carbon::parse($aptTime);
                    $startCarbon = Carbon::parse($newStart);
                    $endCarbon = Carbon::parse($newEnd);

                    if ($aptCarbon->lt($startCarbon) || $aptCarbon->gte($endCarbon)) {
                         return back()->with('error', "Conflict on " . $this->getDayName($dayOfWeek) . ": Appointment for " . $apt->patient->name . " at " . $aptTime . " is outside new hours ($newStart - $newEnd).");
                    }
                }
            }
        }

        foreach ($request->schedules as $data) {
            DoctorSchedule::updateOrCreate(
                [
                    'user_id' => $doctor->id,
                    'day_of_week' => $data['day_of_week'],
                ],
                [
                    'start_time' => $data['start_time'] ?? '08:00',
                    'end_time' => $data['end_time'] ?? '22:00',
                    'is_active' => isset($data['is_active']) ? $data['is_active'] : false,
                ]
            );
        }

        return back()->with('success', 'Doctor schedule updated successfully.');
    }

    private function getDayName($index) {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$index] ?? 'Day';
    }

    // Removed addLeave as per requirement

    public function approveLeave($leaveId)
    {
        $leave = DoctorLeave::findOrFail($leaveId);

        // Conflict Check
        $query = \App\Models\Appointment::where('doctor_id', $leave->user_id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('appointment_date', [$leave->start_date, $leave->end_date]);

        if ($leave->start_time && $leave->end_time) {
             if ($leave->start_date == $leave->end_date) {
                 $query->where(function($q) use ($leave) {
                     $q->where('appointment_time', '>=', $leave->start_time)
                       ->where('appointment_time', '<', $leave->end_time);
                 });
             }
        }

        if ($query->exists()) {
            return back()->with('error', 'Cannot approve leave: Existing appointments conflict with this period. Please resolve conflicts first.');
        }

        $leave->status = 'approved';
        $leave->save();
        
        return back()->with('success', 'Leave approved successfully.');
    }

    public function rejectLeave($leaveId)
    {
        $leave = DoctorLeave::findOrFail($leaveId);
        $leave->status = 'rejected';
        $leave->save();
        
        return back()->with('success', 'Leave rejected successfully.');
    }

    public function deleteLeave($leaveId)
    {
        $leave = DoctorLeave::findOrFail($leaveId);
        $leave->delete();
        return back()->with('success', 'Leave removed successfully.');
    }
}
