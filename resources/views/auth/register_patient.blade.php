@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-4xl')

@section('content')
<div class="py-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 pt-6 pb-2 text-center">
            <h3 class="text-2xl font-bold text-primary dark:text-white">Patient Registration</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Create your account to book appointments</p>
            
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
            <form method="POST" action="{{ route('register.patient.submit') }}" id="regForm">
                @csrf

                <!-- Step 1: Personal Information -->
                <div class="step-section" id="step1">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-person-lines-fill"></i>
                        </span>
                        Personal Information
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IC Number / Passport</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="ic_number" id="ic_number" value="{{ old('ic_number') }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of Birth</label>
                            <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Age</label>
                            <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="age" value="{{ old('age') }}" min="0" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="gender" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
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

                <!-- Step 2: Account Information -->
                <div class="step-section hidden" id="step2">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        Account Information
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
                        <button type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" class="text-white bg-primary hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" onclick="nextStep(3)">
                            Next <i class="bi bi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Medical Information -->
                <div class="step-section hidden" id="step3">
                    <h5 class="text-lg font-bold mb-6 flex items-center text-gray-900 dark:text-white">
                        <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-heart-pulse"></i>
                        </span>
                        Medical Information (Optional)
                    </h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="phone_number" value="{{ old('phone_number') }}" required>
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
                        <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 text-sm">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                        Terms & Consent
                    </h5>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4 border border-gray-200 dark:border-gray-600 max-h-40 overflow-y-auto">
                        <small class="text-gray-500 dark:text-gray-400">
                            By creating an account, you agree to our Terms of Service and Privacy Policy. We collect your data to provide medical services...
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
                        <label for="consentCheck" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I consent to data processing for medical purposes</label>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 inline-flex items-center" onclick="prevStep(3)">
                            <i class="bi bi-arrow-left mr-2"></i> Back
                        </button>
                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Create Patient Account
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
        const icInput = document.getElementById('ic_number');
        const dobInput = document.getElementById('date_of_birth');

        // Function to extract date from IC
        function extractDateFromIC(icValue) {
            const ic = icValue.replace(/[^0-9]/g, '');
            if (ic.length < 6) return null;

            const yearPrefix = ic.substring(0, 2);
            const month = ic.substring(2, 4);
            const day = ic.substring(4, 6);

            // Parse integers
            const mm = parseInt(month, 10);
            const dd = parseInt(day, 10);
            const yy = parseInt(yearPrefix, 10);

            // Basic validation
            if (mm < 1 || mm > 12) return null;
            if (dd < 1 || dd > 31) return null;

            // Determine century
            const currentYear = new Date().getFullYear();
            const currentYearShort = currentYear % 100;
            
            let fullYear = 0;
            if (yy > currentYearShort) {
                fullYear = 1900 + yy;
            } else {
                fullYear = 2000 + yy;
            }

            // Strict date validation (e.g. check for Feb 30, etc.)
            const dateObj = new Date(fullYear, mm - 1, dd);
            if (dateObj.getFullYear() !== fullYear || dateObj.getMonth() + 1 !== mm || dateObj.getDate() !== dd) {
                return null;
            }

            // Format for input type="date" (YYYY-MM-DD)
            return `${fullYear}-${month}-${day}`;
        }

        // Auto-fill listener
        if (icInput && dobInput) {
            const updateDOB = () => {
                const dob = extractDateFromIC(icInput.value);
                if (dob) {
                    dobInput.value = dob;
                }
            };

            icInput.addEventListener('input', updateDOB);
            // Run on load in case of pre-filled data
            updateDOB();
        }

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
