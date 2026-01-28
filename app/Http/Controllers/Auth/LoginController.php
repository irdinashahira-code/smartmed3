<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\ctivity;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->status == 'pending') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is pending approval.');
        }

        if ($user->status == 'rejected') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been rejected.');
        }

        // Log Activity
        ctivity::addToLog('login', 'auth', 'User logged in via ' . ($request->has('google') ? 'Google' : 'Standard Auth'));

        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'patient') {
            return redirect()->route('patient.dashboard');
        } elseif ($user->role == 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        
        return redirect('/home');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }

        // Check if user already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // If user exists, login
            Auth::login($user);
            
            // Check if profile is complete
            if (!$user->ic_number || !$user->phone_number) {
                return redirect()->route('complete.profile');
            }
            
            return redirect($this->redirectTo);
        } else {
            // If user doesn't exist, create a new user
            // Note: Since we don't have all details (IC, Phone, etc.), 
            // we create a basic account. The user might need to update their profile later.
            // We default to 'patient' role as it's the safest default.
            
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(16)), // Random password
                'role' => 'patient', // Default role
                // Other fields are nullable in DB
            ]);

            Auth::login($newUser);

            // New users via Google definitely need to complete profile
            return redirect()->route('complete.profile');
        }
    }
}
