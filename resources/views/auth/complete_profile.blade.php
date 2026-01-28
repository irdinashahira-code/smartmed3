@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-3xl')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 md:p-8">
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Complete Your Profile</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Please provide additional details to finish your registration.</p>
        
        <!-- Progress Bar -->
        <div class="relative m-6">
            <div class="w-full bg-gray-200 rounded-full h-1 dark:bg-gray-700">
                <div class="bg-green-500 h-1 rounded-full transition-all duration-300" style="width: 0%" id="progressBar"></div>
            </div>
            
            <div class="absolute top-0 start-0 -translate-y-1/2 -translate-x-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white text-sm font-bold step-indicator" data-step="1">1</div>
            <div class="absolute top-0 start-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 text-gray-700 text-sm font-bold step-indicator dark:bg-gray-600 dark:text-gray-300" data-step="2" style="left: 50%;">2</div>
            <div class="absolute top-0 start-full -translate-y-1/2 -translate-x-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-gray-300 text-gray-700 text-sm font-bold step-indicator dark:bg-gray-600 dark:text-gray-300" data-step="3">3</div>
        </div>
    </div>

    <form method="POST" action="{{ route('complete.profile.submit') }}" id="regForm">
        @csrf

        <!-- Step 1: Personal Information -->
        <div class="step-section" id="step1">
            <h5 class="text-lg font-bold mb-4 flex items-center text-gray-900 dark:text-white">
                <svg class="w-5 h-5 me-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Personal Information
            </h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1 md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                    <input type="text" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400" name="name" value="{{ old('name', $user->name) }}" readonly>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Name from Google Account</p>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IC Number / Passport</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="ic_number" id="ic_number" value="{{ old('ic_number') }}" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of Birth</label>
                    <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="gender" required>
                        <option value="" selected disabled>Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="phone_number" value="{{ old('phone_number') }}" required>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" onclick="prevStep(1)" disabled>
                    <svg class="w-3.5 h-3.5 me-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/></svg> Back
                </button>
                <button type="button" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" onclick="nextStep(2)">
                    Next <svg class="w-3.5 h-3.5 ms-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
                </button>
            </div>
        </div>

        <!-- Step 2: Address & Emergency Contact -->
        <div class="step-section hidden" id="step2">
            <h5 class="text-lg font-bold mb-4 flex items-center text-gray-900 dark:text-white">
                <svg class="w-5 h-5 me-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                Address & Emergency Contact
            </h5>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1 md:col-span-3">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="address" value="{{ old('address') }}" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="city" value="{{ old('city') }}" required>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
                    <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="state" required>
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
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Postcode</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="postcode" value="{{ old('postcode') }}" required>
                </div>
                
                <div class="col-span-1 md:col-span-3 mt-4">
                    <h6 class="font-bold text-gray-900 dark:text-white">Emergency Contact</h6>
                </div>
                <div class="md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact Name</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact Phone</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Relationship</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" placeholder="e.g. Father, Spouse" required>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" onclick="prevStep(1)">
                    <svg class="w-3.5 h-3.5 me-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/></svg> Back
                </button>
                <button type="button" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" onclick="nextStep(3)">
                    Next <svg class="w-3.5 h-3.5 ms-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
                </button>
            </div>
        </div>

        <!-- Step 3: Terms & Consent -->
        <div class="step-section hidden" id="step3">
            <h5 class="text-lg font-bold mb-4 flex items-center text-gray-900 dark:text-white">
                <svg class="w-5 h-5 me-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                Terms & Consent
            </h5>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200 dark:bg-gray-700 dark:border-gray-600" style="max-height: 150px; overflow-y: auto;">
                <small class="text-gray-500 dark:text-gray-400">
                    By creating an account, you agree to our Terms of Service and Privacy Policy. We collect your data to provide medical services...
                </small>
            </div>

            <div class="flex items-start mb-4">
                <div class="flex items-center h-5">
                    <input id="termsCheck" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required>
                </div>
                <label for="termsCheck" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">I agree to the Terms and Conditions</label>
            </div>
            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="consentCheck" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required>
                </div>
                <label for="consentCheck" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">I consent to data processing for medical purposes</label>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" onclick="prevStep(2)">
                    <svg class="w-3.5 h-3.5 me-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/></svg> Back
                </button>
                <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Complete Registration
                </button>
            </div>
        </div>

    </form>
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
                    input.classList.add('border-red-500');
                    input.classList.remove('border-gray-300', 'dark:border-gray-600');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-gray-300', 'dark:border-gray-600');
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
            const progress = ((step - 1) / 2) * 100; // 3 steps total
            document.getElementById('progressBar').style.width = progress + '%';

            // Update Indicators
            const indicators = document.querySelectorAll('.step-indicator');
            indicators.forEach((btn, index) => {
                 // index is 0-based, step is 1-based
                 const btnStep = index + 1;
                 
                 if(btnStep <= step) {
                     // Active or completed
                     btn.classList.remove('bg-gray-300', 'text-gray-700', 'dark:bg-gray-600', 'dark:text-gray-300');
                     btn.classList.add('bg-primary-600', 'text-white');
                 } else {
                     // Inactive
                     btn.classList.remove('bg-primary-600', 'text-white');
                     btn.classList.add('bg-gray-300', 'text-gray-700', 'dark:bg-gray-600', 'dark:text-gray-300');
                 }
            });

            currentStep = step;
        }
    });
</script>
@endsection