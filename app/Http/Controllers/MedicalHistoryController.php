<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalHistory;
use App\Models\ConsultationNote;
use Illuminate\Support\Facades\Auth;

class MedicalHistoryController extends Controller
{
    public function index()
    {
        $medicalHistories = Auth::user()->medicalHistories()->orderBy('created_at', 'desc')->get();
        $consultations = ConsultationNote::where('user_id', Auth::id())
            ->with(['doctor', 'appointment.medicalImages'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('patient.medical_history.index', compact('medicalHistories', 'consultations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'condition' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'diagnosed_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,recovered,chronic',
        ]);

        MedicalHistory::create([
            'user_id' => Auth::id(),
            'condition' => $request->condition,
            'description' => $request->description,
            'diagnosed_date' => $request->diagnosed_date,
            'status' => $request->status ?? 'active',
        ]);

        return back()->with('success', 'Medical record added successfully.');
    }

    public function destroy(MedicalHistory $medicalHistory)
    {
        if ($medicalHistory->user_id !== Auth::id()) {
            abort(403);
        }

        $medicalHistory->delete();

        return back()->with('success', 'Medical record removed successfully.');
    }
}
