<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorStreamController extends Controller
{
    public function stream(Request $request)
    {
        // Ensure user is authenticated and is a doctor (though middleware should handle auth)
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
            
            // 2 minutes max execution to prevent zombie processes
            while (true) {
                if (connection_aborted() || (time() - $startTime > 120)) {
                    break;
                }

                // Fetch upcoming appointments
                // Filter strict future appointments (Date > Today OR (Date = Today AND Time > Now))
                $today = Carbon::today()->format('Y-m-d');
                $currentTime = Carbon::now()->format('H:i:s');
                
                $appointments = Appointment::with('patient')
                    ->where('doctor_id', $user->id)
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

                // Format data for the dashboard
                $data = $appointments->map(function ($apt) {
                    return [
                        'id' => $apt->id,
                        'patient_name' => $apt->patient->name,
                        'ic_number' => $apt->patient->ic_number,
                        'date' => $apt->appointment_date, // Casted to string by model usually
                        'time' => $apt->appointment_time,
                        'type' => $this->getStatusBadgeInfo($apt),
                        'reason' => $apt->reason,
                        'cancellation_status' => $apt->cancellation_status,
                        'reschedule_status' => $apt->reschedule_status,
                        'status' => $apt->status,
                        'is_new' => $apt->created_at->diffInMinutes(Carbon::now()) < 5 // Flag as new if created in last 5 mins
                    ];
                });

                $currentHash = md5($data->toJson());

                if ($currentHash !== $oldHash) {
                    echo "data: " . $data->toJson() . "\n\n";
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

    private function getStatusBadgeInfo($appointment)
    {
        if ($appointment->cancellation_status == 'pending') {
            return ['label' => 'Cancellation Pending', 'class' => 'bg-warning text-dark'];
        } elseif ($appointment->reschedule_status == 'pending') {
            return ['label' => 'Reschedule Pending', 'class' => 'bg-info text-dark'];
        } else {
            return ['label' => 'Confirmed', 'class' => 'bg-success'];
        }
    }
}
