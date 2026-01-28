<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showDoctorRegistrationForm()
    {
        return view('auth.register_doctor');
    }

    public function registerDoctor(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:50'],
            'ic_number' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'staff_id' => ['required', 'string', 'max:50'],
            'secret_key' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($value !== env('DOCTOR_SECRET_KEY')) {
                    $fail('The ' . $attribute . ' is invalid.');
                }
            }],
            'specialization' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'ic_number' => $data['ic_number'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'postcode' => $data['postcode'],
            'staff_id' => $data['staff_id'],
            'secret_key' => $data['secret_key'],
            'specialization' => $data['specialization'],
            'role' => 'doctor',
            'status' => 'pending',
            'password' => Hash::make($data['password']),
        ]);

        // Do not auto-login doctor
        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending approval by the administrator.');
    }

    public function showPatientRegistrationForm()
    {
        return view('auth.register_patient');
    }

    public function registerPatient(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'ic_number' => ['required', 'string', 'max:50'],
            'age' => ['nullable', 'integer', 'min:0'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'phone_number' => ['required', 'string', 'max:50'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:50'],
            'emergency_contact_relationship' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'ic_number' => $data['ic_number'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'postcode' => $data['postcode'],
            'phone_number' => $data['phone_number'],
            'blood_type' => $data['blood_type'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'emergency_contact_relationship' => $data['emergency_contact_relationship'],
            'role' => 'patient',
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect($this->redirectTo);
    }
}
