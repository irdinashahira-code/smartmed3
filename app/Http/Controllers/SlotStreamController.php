<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\AppointmentController;

class SlotStreamController extends Controller
{
    protected $appointmentController;

    public function __construct(AppointmentController $appointmentController)
    {
        $this->appointmentController = $appointmentController;
    }

    public function stream(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'doctor_id' => 'nullable|exists:users,id'
        ]);

        $date = $request->date;
        $doctorId = $request->doctor_id;

        return response()->stream(function () use ($date, $doctorId) {
            // Close session to prevent locking other requests from the same user
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            $oldHash = null;
            
            // 2 minutes max execution
            $startTime = time();
            
            while (true) {
                if (connection_aborted() || (time() - $startTime > 120)) {
                    break;
                }

                // Using the calculateSlots method we extracted
                $slots = $this->appointmentController->calculateSlots($date, $doctorId);
                $newHash = md5(json_encode($slots));

                if ($newHash !== $oldHash) {
                    echo "data: " . json_encode($slots) . "\n\n";
                    ob_flush();
                    flush();
                    $oldHash = $newHash;
                }

                // Heartbeat to keep connection open
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
}
