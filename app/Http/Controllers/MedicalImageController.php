<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $appointment = \App\Models\Appointment::findOrFail($request->appointment_id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file (Simulating Cloud Storage with 'public' disk for now)
            // In a real netcentric setup, you would change 'public' to 's3' or 'google'
            $path = $file->storeAs('medical_images', $fileName, 'public');

            // Save Metadata to DB
            $medicalImage = \App\Models\MedicalImage::create([
                'user_id' => $appointment->user_id, // Patient
                'appointment_id' => $appointment->id,
                'uploaded_by' => auth()->id(), // Doctor
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'disk' => 'public', // Change this dynamically if using S3
            ]);

            return response()->json([
                'success' => true,
                'image' => [
                    'id' => $medicalImage->id,
                    'file_name' => $medicalImage->file_name,
                    'url' => asset('storage/' . $path), // Generates URL for display
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }
}
