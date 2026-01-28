<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\DoctorLeave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $doctor = Auth::user();
        $schedules = $doctor->schedules()->orderBy('day_of_week')->get();
        $leaves = $doctor->leaves()
            ->where('end_date', '>=', now()->toDateString())
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('start_date')
            ->get();

        // Prepare default schedule structure if empty
        $weekDays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('doctor.schedule.index', compact('schedules', 'leaves', 'weekDays'));
    }

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_active' => 'boolean',
        ]);

        $doctor = Auth::user();

        // 1. Conflict Check
        foreach ($request->schedules as $data) {
            $dayOfWeek = $data['day_of_week'];
            $isActive = isset($data['is_active']) ? $data['is_active'] : false;
            
            // Get all future appointments for this specific day of week
            // DAYOFWEEK in MySQL returns 1=Sun, 7=Sat. PHP $dayOfWeek is 0=Sun, 6=Sat.
            // So MySQL DAYOFWEEK = PHP + 1.
            $futureAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', '>=', now()->toDateString())
                ->where('status', '!=', 'cancelled')
                ->whereRaw("DAYOFWEEK(appointment_date) = ?", [$dayOfWeek + 1])
                ->get();

            if ($futureAppointments->isEmpty()) {
                continue;
            }

            if (!$isActive) {
                return back()->with('error', "Cannot disable " . $this->getDayName($dayOfWeek) . " as you have existing future appointments on this day.");
            } else {
                // Check time boundaries
                $newStart = $data['start_time'] ?? '08:00';
                $newEnd = $data['end_time'] ?? '22:00';

                foreach ($futureAppointments as $apt) {
                    $aptTime = $apt->appointment_time; // H:i:s
                    // Simple string comparison usually works for H:i:s vs H:i if padded, but let's use Carbon
                    $aptCarbon = Carbon::parse($aptTime);
                    $startCarbon = Carbon::parse($newStart);
                    $endCarbon = Carbon::parse($newEnd);

                    // We need to compare only TIME part.
                    // Carbon::parse('09:00') creates today 09:00.
                    // Carbon::parse($aptTime) creates today H:i:s.
                    // So comparison is safe.

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

        return back()->with('success', 'Schedule updated successfully.');
    }

    private function getDayName($index) {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$index] ?? 'Day';
    }

    public function addLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'reason' => 'nullable|string|max:255',
        ]);

        $doctor = Auth::user();

        // Conflict Check
        $query = \App\Models\Appointment::where('doctor_id', $doctor->id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('appointment_date', [$request->start_date, $request->end_date]);

        // Refined conflict check for partial days
        if ($request->start_time && $request->end_time) {
             // If leave is for specific hours, only conflict if appointment is within those hours
             // Note: This logic assumes start_date == end_date for partial leaves usually, but user might select range.
             // If range + time, it implies "Leave every day in this range between these times" OR "Leave from start_date T start_time to end_date T end_time"?
             // Standard interpretation for simple leave is: 
             // If start_date != end_date, it's a multi-day leave. Time usually applies to start/end boundaries or ignored (full days).
             // Let's assume: If start_date == end_date, time applies. If different, time applies to start of first day and end of last day?
             // To keep it simple and robust:
             // If times are provided, check intersection.
             
             if ($request->start_date == $request->end_date) {
                 $query->where(function($q) use ($request) {
                     $q->where('appointment_time', '>=', $request->start_time)
                       ->where('appointment_time', '<', $request->end_time);
                 });
             }
             // If multi-day with time, it's complex. Let's assume full day for multi-day for safety, or warn user.
             // But let's stick to: "If time provided, it effectively means 'unavailable slot' on that specific date".
        }

        if ($query->exists()) {
            return back()->with('error', 'You have existing appointments during this leave period. Please reschedule them first.');
        }

        DoctorLeave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Leave/Unavailable slot request submitted for approval.');
    }

    public function deleteLeave(DoctorLeave $leave)
    {
        if ($leave->user_id !== Auth::id()) {
            abort(403);
        }

        $leave->delete();

        return back()->with('success', 'Leave removed successfully.');
    }
}
