@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-4xl')

@section('content')
<div class="py-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 pt-6 pb-2 text-center">
            <h3 class="text-2xl font-bold text-primary dark:text-white">Doctor Registration</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Join our medical team</p>
            
            <!-- Progress Bar -->
            <div class="relative m-8 mx-12">
                <div class="w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700">
                    <div class="bg-primary h-1 rounded-full transition-all duration-300" style="width: 0%;" id="progressBar"></div>
                </div>
                
                <!-- Step 1 Indicator -->
                <button type="button" class="step-btn absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 bg-primary text-white ring-4 ring-white dark:ring-gray-800" data-step="1">1</button>
                
                <!-- Step 2 Indicator -->
                <button type="button" class="step-btn absolute top-0 left-1/3 -translate-x-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 bg-gray-200 text-gray-500 ring-4 ring-white dark:ring-gray-800 dark:bg-gray-700 dark:text-gray-400" data-step="2">2</button>
                
                <!-- Step 3 Indicator -->
                <button type="button" class="step-btn absolute top-0 left-2/3 -translate-x-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 bg-gray-200 text-gray-500 ring-4 ring-white dark:ring-gray-800 dark:bg-gray-700 dark:text-gray-400" data-step="3">3</button>
                
                <!-- Step 4 Indicator -->
                <button type="button" class="step-btn absolute top-0 left-full -translate-x-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 bg-gray-200 text-gray-500 ring-4 ring-white dark:ring-gray-800 dark:bg-gray-700 dark:text-gray-400" data-step="4">4</button>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <form method="POST" action="{{ route('register.doctor.submit') }}" id="regForm">
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="step-section" id="step1">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        Personal Information
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IC Number</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="ic_number" value="{{ old('ic_number') }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="tel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="phone_number" value="{{ old('phone_number') }}" required>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="address" value="{{ old('address') }}" required>
                        </div>
                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="city" value="{{ old('city') }}" required>
                        </div>
                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="state" required>
                                <option value="" selected disabled>Select State</option>
                                <option value="Johor" {{ old('state') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                <option value="Kedah" {{ old('state') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                <option value="Kelantan" {{ old('state') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                <option value="Melaka" {{ old('state') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                <option value="Negeri Sembilan" {{ old('state') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                <option value="Pahang" {{ old('state') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                <option value="Perak" {{ old('state') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                <option value="Perlis" {{ old('state') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                <option value="Pulau Pinang" {{ old('state') == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                <option value="Sabah" {{ old('state') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                <option value="Sarawak" {{ old('state') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                <option value="Selangor" {{ old('state') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                <option value="Terengganu" {{ old('state') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                <option value="Kuala Lumpur" {{ old('state') == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                <option value="Labuan" {{ old('state') == 'Labuan' ? 'selected' : '' }}>Labuan</option>
                                <option value="Putrajaya" {{ old('state') == 'Putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                            </select>
                        </div>
                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Postcode</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="postcode" value="{{ old('postcode') }}" required>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-right">
                        <button type="button" class="text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" onclick="nextStep(2)">
                            Next <i class="bi bi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Professional Details -->
                <div class="step-section hidden" id="step2">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-briefcase-medical"></i>
                        </span>
                        Professional Details
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Staff ID</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="staff_id" value="{{ old('staff_id') }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Specialization</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="specialization" value="{{ old('specialization') }}" required>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Secret Key (Verification)</label>
                            <input type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="secret_key" required>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required for doctor verification.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" class="text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" onclick="nextStep(3)">
                            Next <i class="bi bi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Account Security -->
                <div class="step-section hidden" id="step3">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        Account Security
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                            <input type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="password" required minlength="8">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Min. 8 characters</p>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                            <input type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center" onclick="prevStep(2)">
                            <i class="bi bi-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" class="text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" onclick="nextStep(4)">
                            Next <i class="bi bi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Terms & Consent -->
                <div class="step-section hidden" id="step4">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                        Terms & Consent
                    </h5>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4 border border-gray-200 dark:border-gray-600 max-h-40 overflow-y-auto">
                        <small class="text-gray-500 dark:text-gray-400">
                            By creating a doctor account, you agree to our Terms of Service and Privacy Policy. You also certify that all professional information provided is accurate and verifiable...
                        </small>
                    </div>

                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input id="termsCheck" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required>
                        </div>
                        <label for="termsCheck" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I agree to the Terms and Conditions</label>
                    </div>
                    <div class="flex items-start mb-6">
                        <div class="flex items-center h-5">
                            <input id="consentCheck" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required>
                        </div>
                        <label for="consentCheck" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I certify that I am a licensed medical professional</label>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center" onclick="prevStep(3)">
                            <i class="bi bi-arrow-left mr-2"></i> Back
                        </button>
                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Register as Doctor
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentStep = 1;

        window.nextStep = function(step) {
            // Simple validation for current step inputs
            const currentSection = document.getElementById('step' + currentStep);
            const inputs = currentSection.querySelectorAll('input[required], select[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value) {
                    input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.remove('border-gray-300', 'focus:border-primary', 'focus:ring-primary');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.add('border-gray-300', 'focus:border-primary', 'focus:ring-primary');
                }
            });

            if (!valid) return;

            showStep(step);
        }

        window.prevStep = function(step) {
            showStep(step);
        }

        window.showStep = function(step) {
            document.querySelectorAll('.step-section').forEach(el => el.classList.add('hidden'));
            document.getElementById('step' + step).classList.remove('hidden');
            
            // Update Progress Bar
            const progress = ((step - 1) / 3) * 100;
            document.getElementById('progressBar').style.width = progress + '%';

            // Update Indicators
            const buttons = document.querySelectorAll('.step-btn');
            
            buttons.forEach(btn => {
                const btnStep = parseInt(btn.getAttribute('data-step'));
                if(btnStep <= step) {
                    // Active or Completed Step
                    btn.classList.remove('bg-gray-200', 'text-gray-500', 'dark:bg-gray-700', 'dark:text-gray-400');
                    btn.classList.add('bg-primary', 'text-white');
                } else {
                    // Future Step
                    btn.classList.remove('bg-primary', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-500', 'dark:bg-gray-700', 'dark:text-gray-400');
                }
            });

            currentStep = step;
        }
    });
</script>
@endsection
