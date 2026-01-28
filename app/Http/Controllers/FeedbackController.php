<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Ensure user owns the appointment
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Check if feedback already exists
        if (Feedback::where('appointment_id', $appointment->id)->exists()) {
             return redirect()->route('patient.appointments.history')->with('error', 'You have already submitted feedback for this appointment.');
        }

        Feedback::create([
            'user_id' => Auth::id(),
            'appointment_id' => $appointment->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Thank you for your feedback!');
    }
}
