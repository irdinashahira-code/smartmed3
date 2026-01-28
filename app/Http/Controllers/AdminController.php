<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;
use App\Models\MedicalHistory;
use App\Models\ConsultationNote;

class AdminController extends Controller
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

    public function dashboard()
    {
        $totalPatients = User::where('role', 'patient')->count();
        $totalDoctors = User::where('role', 'doctor')->count();
        $pendingDoctors = User::where('role', 'doctor')->where('status', 'pending')->count();
        
        // Top Rated Doctors
        $topRatedDoctors = User::where('role', 'doctor')
            ->whereHas('feedbacks')
            ->withAvg('feedbacks', 'rating')
            ->withCount('feedbacks')
            ->having('feedbacks_avg_rating', '>=', 3)
            ->orderByDesc('feedbacks_avg_rating')
            ->take(5)
            ->get();
            
        // Lowest Rated Doctors
        $lowestRatedDoctors = User::where('role', 'doctor')
            ->whereHas('feedbacks')
            ->withAvg('feedbacks', 'rating')
            ->withCount('feedbacks')
            ->having('feedbacks_avg_rating', '<', 3)
            ->orderBy('feedbacks_avg_rating')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact('totalPatients', 'totalDoctors', 'pendingDoctors', 'topRatedDoctors', 'lowestRatedDoctors'));
    }

    // --- Doctor Management ---

    public function doctors(Request $request)
    {
        $query = User::where('role', 'doctor');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function createDoctor()
    {
        return view('admin.doctors.create');
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string',
            'qualification' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'status' => 'active', // Admin created doctors are active by default
            'specialization' => $request->specialization,
            'qualification' => $request->qualification,
            'bio' => $request->bio,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('admin.doctors')->with('success', 'Doctor account created successfully.');
    }

    public function editDoctor($id)
    {
        $doctor = User::findOrFail($id);
        if ($doctor->role !== 'doctor') abort(404);
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function updateDoctor(Request $request, $id)
    {
        $doctor = User::findOrFail($id);
        if ($doctor->role !== 'doctor') abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'specialization' => 'required|string',
            'qualification' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'status' => 'required|in:active,pending,rejected',
        ]);

        $doctor->update($request->all());

        return redirect()->route('admin.doctors')->with('success', 'Doctor profile updated successfully.');
    }

    public function approveDoctor($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->status = 'active';
        $doctor->save();

        return redirect()->back()->with('success', 'Doctor approved successfully.');
    }

    public function rejectDoctor($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->status = 'rejected';
        $doctor->save();

        return redirect()->back()->with('success', 'Doctor rejected successfully.');
    }

    // --- Patient Management ---

    public function patients(Request $request)
    {
        $query = User::where('role', 'patient');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ic_number', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    public function showPatient($id)
    {
        $patient = User::findOrFail($id);
        if ($patient->role !== 'patient') abort(404);
        return view('admin.patients.show', compact('patient'));
    }

    public function deletePatient($id)
    {
        $patient = User::findOrFail($id);
        if ($patient->role !== 'patient') abort(404);

        if ($patient->status !== 'inactive') {
            return back()->with('error', 'Only inactive patients can be deleted. Please deactivate the patient first.');
        }

        $patient->delete();

        return redirect()->route('admin.patients')->with('success', 'Patient account deleted successfully.');
    }

    public function togglePatientStatus($id)
    {
        $patient = User::findOrFail($id);
        if ($patient->role !== 'patient') abort(404);

        $patient->status = $patient->status === 'active' ? 'inactive' : 'active';
        $patient->save();

        $message = $patient->status === 'active' ? 'Patient activated successfully.' : 'Patient deactivated successfully.';
        return back()->with('success', $message);
    }

    public function patientMedicalHistory($id)
    {
        $patient = User::findOrFail($id);
        if ($patient->role !== 'patient') abort(404);

        $medicalHistories = MedicalHistory::where('user_id', $patient->id)->orderBy('diagnosed_date', 'desc')->get();
        
        // Fetch consultation notes for this patient from ALL doctors
        $consultations = ConsultationNote::where('user_id', $patient->id)
            ->with(['doctor', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.patients.medical_history', compact('patient', 'medicalHistories', 'consultations'));
    }

    // --- Appointment Management ---

    public function appointments(Request $request)
    {
        $query = Appointment::with(['doctor', 'patient']);

        if ($request->has('date') && $request->date != '') {
            $query->where('appointment_date', $request->date);
        }

        if ($request->has('ic_number') && $request->ic_number != '') {
            $ic = $request->ic_number;
            $query->where(function($q) use ($ic) {
                $q->where('patient_ic', 'like', '%' . $ic . '%')
                  ->orWhereHas('patient', function($subQ) use ($ic) {
                      $subQ->where('ic_number', 'like', '%' . $ic . '%');
                  });
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();
            
        return view('admin.appointments.index', compact('appointments'));
    }
}
