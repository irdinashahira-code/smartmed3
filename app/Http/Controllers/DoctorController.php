<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user();
        
        // Show pending cancellation requests for THIS doctor
        $cancellationRequests = Appointment::where('doctor_id', $doctor->id)
            ->where('cancellation_status', 'pending')
            ->orderBy('appointment_date', 'asc')
            ->get();
            
        // Show pending reschedule requests for THIS doctor
        $rescheduleRequests = Appointment::where('doctor_id', $doctor->id)
            ->where('reschedule_status', 'pending')
            ->orderBy('appointment_date', 'asc')
            ->get();
            
        // Upcoming confirmed appointments for THIS doctor
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');
        
        // Count today's appointments for THIS doctor
        $todayAppointmentsCount = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $today)
            ->count();
        
        // Show all upcoming appointments for THIS doctor
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
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

        return view('doctor.dashboard', compact('cancellationRequests', 'rescheduleRequests', 'upcomingAppointments', 'todayAppointmentsCount'));
    }

    public function todayAppointments()
    {
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $today = $now->format('Y-m-d');
        $doctor = Auth::user();
        
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $today)
            ->orderBy('appointment_time', 'asc') // Ordered by time slot as requested
            ->get();
            
        return view('doctor.appointments.today', compact('todayAppointments'));
    }

    public function approveCancel(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_status' => 'approved'
        ]);

        return back()->with('success', 'Cancellation approved.');
    }

    public function approveReschedule(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $newData = $appointment->reschedule_data;
        
        if (!$newData) {
            return back()->with('error', 'No reschedule data found.');
        }

        $appointment->update([
            'appointment_date' => $newData['date'],
            'appointment_time' => $newData['time'],
            'doctor_id' => $newData['doctor_id'] ?? $appointment->doctor_id,
            'weight' => $newData['weight'] ?? $appointment->weight,
            'type' => $newData['type'] ?? $appointment->type,
            'reason' => $newData['reason'] ?? $appointment->reason,
            'reschedule_status' => 'approved',
            'reschedule_data' => null
        ]);

        return back()->with('success', 'Reschedule approved.');
    }

    public function getAppointmentDetails(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this appointment.');
        }

        $patient = $appointment->patient;
        
        // 1. Patient Medical History (registered by patient)
        $medicalHistory = $patient->medicalHistories;

        // 2. Previous Consultation Notes (by this doctor)
        $previousNotes = \App\Models\ConsultationNote::where('doctor_id', Auth::id())
            ->where('user_id', $patient->id)
            ->where('appointment_id', '!=', $appointment->id)
            ->with('appointment')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Current Note (if exists)
        $currentNote = $appointment->consultationNote;

        // 4. Medical Images (uploaded for this appointment)
        $medicalImages = \App\Models\MedicalImage::where('appointment_id', $appointment->id)->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'file_name' => $image->file_name,
                'url' => asset('storage/' . $image->file_path), // Ensure this path is correct based on your filesystem config
            ];
        });

        // Add profile photo URL to patient object
        $patient->profile_photo_url = $patient->profile_photo_path 
            ? asset('storage/' . $patient->profile_photo_path) 
            : null;

        return response()->json([
            'appointment' => $appointment,
            'patient' => $patient,
            'medical_history' => $medicalHistory,
            'previous_notes' => $previousNotes,
            'current_note' => $currentNote,
            'medical_images' => $medicalImages
        ]);
    }

    public function saveConsultationNotes(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'private_notes' => 'nullable|string',
        ]);

        \App\Models\ConsultationNote::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'doctor_id' => Auth::id(),
                'user_id' => $appointment->user_id,
                'diagnosis' => $request->diagnosis,
                'treatment' => $request->treatment,
                'prescription' => $request->prescription,
                'private_notes' => $request->private_notes,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Notes saved successfully.']);
    }

    public function feedback()
    {
        $feedbacks = Auth::user()->feedbacks()->with('appointment.patient')->latest()->paginate(10);
        return view('doctor.feedback', compact('feedbacks'));
    }
}
