@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-2xl')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Join SmartMed</h2>
        <p class="text-gray-500 dark:text-gray-400">Choose your account type to get started</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Patient Card -->
        <div id="card-patient" class="selection-card cursor-pointer border-2 border-transparent rounded-xl p-6 text-center bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition-all duration-300 shadow-sm hover:shadow-md h-full flex flex-col items-center justify-center" onclick="selectType('patient')">
            <div class="mb-4 text-primary text-6xl">
                <i class="bi bi-person-heart"></i>
            </div>
            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Patient</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Book appointments and manage your health records.</p>
            <div id="check-patient" class="hidden text-success text-3xl mt-2 animate-bounce">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        <!-- Doctor Card -->
        <div id="card-doctor" class="selection-card cursor-pointer border-2 border-transparent rounded-xl p-6 text-center bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition-all duration-300 shadow-sm hover:shadow-md h-full flex flex-col items-center justify-center" onclick="selectType('doctor')">
            <div class="mb-4 text-success text-6xl">
                <i class="bi bi-person-workspace"></i>
            </div>
            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Doctor</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Manage patients, appointments, and consultations.</p>
            <div id="check-doctor" class="hidden text-success text-3xl mt-2 animate-bounce">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
    </div>

    <div class="text-center mb-8 max-w-md mx-auto">
        <button id="btn-continue" class="w-full text-white bg-primary hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all" disabled onclick="proceedRegistration()">
            Continue <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
    
    <div class="flex items-center justify-between mb-6 max-w-md mx-auto">
        <div class="h-px bg-gray-300 flex-grow dark:bg-gray-600"></div>
        <span class="px-3 text-gray-500 text-sm font-medium dark:text-gray-400">OR</span>
        <div class="h-px bg-gray-300 flex-grow dark:bg-gray-600"></div>
    </div>
    
    <div class="text-center mb-6 max-w-md mx-auto">
        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
            <i class="bi bi-google me-2 text-red-500"></i> Sign up with Google
        </a>
        <small class="text-gray-500 dark:text-gray-400 block mt-2">(Defaults to Patient Account)</small>
    </div>

    <div class="text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">Already have an account? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline dark:text-blue-500">Log in</a></p>
    </div>
</div>

<script>
    let selectedType = null;

    function selectType(type) {
        selectedType = type;
        
        // Reset styles
        document.querySelectorAll('.selection-card').forEach(el => {
            el.classList.remove('border-primary', 'bg-blue-50', 'dark:bg-gray-600');
            // Remove check marks
            el.querySelector('[id^="check-"]').classList.add('hidden');
        });
        
        // Apply active style
        const card = document.getElementById('card-' + type);
        card.classList.add('border-primary', 'bg-blue-50', 'dark:bg-gray-600');
        
        // Show check mark
        document.getElementById('check-' + type).classList.remove('hidden');
        
        // Enable button
        document.getElementById('btn-continue').disabled = false;
    }

    function proceedRegistration() {
        if (selectedType === 'patient') {
            window.location.href = "{{ route('register.patient') }}";
        } else if (selectedType === 'doctor') {
            window.location.href = "{{ route('register.doctor') }}";
        }
    }
</script>
@endsection
