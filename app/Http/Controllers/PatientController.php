<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\PatientAdPreference;
use App\Models\AdInteraction;
use App\Models\Appointment;
use App\Models\MedicalHistory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // 1. Upcoming Appointments Count
        // Count appointments that are active (not cancelled/completed) and in the future
        $now = Carbon::now('Asia/Kuala_Lumpur');
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        $upcomingAppointmentsCount = Appointment::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled', 'completed', 'rejected'])
            ->where(function($query) use ($today, $currentTime) {
                $query->where('appointment_date', '>', $today)
                      ->orWhere(function($q) use ($today, $currentTime) {
                          $q->where('appointment_date', $today)
                            ->where('appointment_time', '>', $currentTime);
                      });
            })
            ->count();

        // 2. Medical Records Count
        // Counting MedicalHistory entries
        $medicalDocumentsCount = MedicalHistory::where('user_id', $user->id)->count();

        // 3. Profile Completion Percentage
        $profileFields = [
            'name', 'email', 'phone_number', 'ic_number', 'date_of_birth', 
            'gender', 'address', 'city', 'state', 'postcode', 
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
            'profile_photo_path' // Optional, but good to include if we want 100% to mean "fully fully" populated
        ];
        
        // Remove profile_photo_path from mandatory check if we want strict text fields only
        // Let's stick to core text fields for "Profile Completion" as photo is often optional
        $coreProfileFields = [
             'name', 'email', 'phone_number', 'ic_number', 'date_of_birth', 
             'gender', 'address', 'city', 'state', 'postcode', 
             'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'
        ];

        $filledFields = 0;
        foreach ($coreProfileFields as $field) {
            if (!empty($user->$field)) {
                $filledFields++;
            }
        }
        $profileCompletion = count($coreProfileFields) > 0 ? round(($filledFields / count($coreProfileFields)) * 100) : 0;

        // Fetch ads logic
        $ads = $this->getTargetedAds($user);

        return view('patient.dashboard', compact('ads', 'upcomingAppointmentsCount', 'medicalDocumentsCount', 'profileCompletion'));
    }

    private function getTargetedAds($user)
    {
        $today = Carbon::today();
        
        // 1. Get user preferences
        $prefs = PatientAdPreference::where('user_id', $user->id)->first();
        $optOuts = $prefs ? $prefs->opt_out_types : [];
        if (!$optOuts) $optOuts = [];

        // 2. Base Query
        $query = Advertisement::where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);

        if (!empty($optOuts)) {
            $query->whereNotIn('type', $optOuts);
        }

        $allAds = $query->orderBy('priority', 'desc')->get();
        
        $filteredAds = $allAds->filter(function($ad) use ($user) {
            // Gender check (if available in ad)
            if ($ad->target_gender !== 'all') {
                // Infer gender from IC if possible (last digit odd=male, even=female)
                $ic = $user->ic_number;
                if ($ic && is_numeric($ic)) {
                    $lastDigit = substr($ic, -1);
                    $gender = ($lastDigit % 2 == 0) ? 'female' : 'male';
                    if ($ad->target_gender !== $gender) return false;
                }
            }
            
            // Age check (if available in ad)
            if ($ad->target_age_min || $ad->target_age_max) {
                 // Simplified age calc from IC (YY...)
                 // Assume Malaysian IC: YYMMDD-PB-###G
                 $ic = $user->ic_number;
                 if ($ic && strlen($ic) >= 2) {
                     $year = intval(substr($ic, 0, 2));
                     // Simple heuristic: if year > current year last 2 digits, it's 19YY, else 20YY
                     $currentYearShort = intval(date('y'));
                     $fullYear = ($year > $currentYearShort) ? 1900 + $year : 2000 + $year;
                     $age = date('Y') - $fullYear;
                     
                     if ($ad->target_age_min && $age < $ad->target_age_min) return false;
                     if ($ad->target_age_max && $age > $ad->target_age_max) return false;
                 }
            }

            // Medical Condition check
            if (!empty($ad->target_conditions) && is_array($ad->target_conditions) && count($ad->target_conditions) > 0) {
                 $hasCondition = $user->medicalHistories()
                    ->whereIn('condition', $ad->target_conditions)
                    ->exists();
                 if (!$hasCondition) return false;
            }

            return true;
        });

        // 4. Randomize/Rotate & Session Check
        $shownAds = session('shown_ad_ids', []);
        
        // Filter out ads shown in this session (unless we ran out)
        // Use reject with loose comparison to handle string/int types in session
        $candidates = $filteredAds->reject(function ($ad) use ($shownAds) {
            return in_array($ad->id, $shownAds);
        });
        
        // If we have fewer candidates than needed (e.g. < 1), reset session to show ads again
        if ($candidates->isEmpty() && $filteredAds->isNotEmpty()) {
             session(['shown_ad_ids' => []]);
             $candidates = $filteredAds;
             $shownAds = []; // Reset local variable
        }

        // Take up to 3 ads
        $count = min($candidates->count(), 3);
        $selectedAds = $count > 0 ? $candidates->random($count) : collect([]);

        // Update session with displayed ads
        if ($selectedAds->isNotEmpty()) {
            $newShownIds = array_unique(array_merge($shownAds, $selectedAds->pluck('id')->toArray()));
            session(['shown_ad_ids' => $newShownIds]);
        }

        return $selectedAds;
    }

    public function trackAdInteraction(Request $request)
    {
        $request->validate([
            'ad_id' => 'required|exists:advertisements,id',
            'type' => 'required|in:impression,click'
        ]);

        AdInteraction::create([
            'advertisement_id' => $request->ad_id,
            'user_id' => Auth::id(),
            'interaction_type' => $request->type
        ]);

        return response()->json(['status' => 'success']);
    }
}
