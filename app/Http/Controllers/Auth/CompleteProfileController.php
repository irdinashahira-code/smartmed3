<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CompleteProfileController extends Controller
{
    /**
     * Show the form for completing the profile.
     */
    public function showForm()
    {
        $user = Auth::user();

        // If profile is already complete, redirect to home
        if ($user->ic_number && $user->phone_number && $user->address) {
            return redirect()->route('home');
        }

        return view('auth.complete_profile', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'ic_number' => 'required|string|max:20|unique:users,ic_number,' . $user->id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => 'nullable|string|max:15',
            'emergency_contact_relationship' => 'nullable|string|max:50',
        ]);

        /** @var User $user */
        $user->update([
            'ic_number' => $validated['ic_number'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postcode' => $validated['postcode'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'],
        ]);

        return redirect()->route('home')->with('status', 'Profile completed successfully!');
    }
}
