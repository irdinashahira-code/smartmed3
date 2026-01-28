@extends('layouts.patient_nextkit')

@section('title', 'Dashboard')

@section('content')

{{-- Hero Section --}}
<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm p-8 md:p-12 mb-8 border border-gray-100 dark:border-gray-700 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-lightprimary blur-3xl opacity-50"></div>
    <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-secondary/20 blur-2xl opacity-50"></div>
    
    <!-- Stethoscope Background Design -->
    <div class="absolute right-[-2rem] bottom-[-2rem] pointer-events-none opacity-15 transform rotate-[-15deg]">
        <i class="bi bi-prescription2 text-[15rem] text-primary"></i>
    </div>
    
    <div class="relative z-10 max-w-3xl">
        @if (session('status'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <h1 class="text-3xl md:text-5xl font-extrabold text-navy dark:text-white mb-4 leading-tight">
            Your Health, <span class="text-primary">Reimagined.</span>
        </h1>
        <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-relaxed max-w-xl">
            Welcome back, <span class="font-semibold text-navy dark:text-gray-200">{{ Auth::user()->name }}</span>. Access your medical records, schedule appointments, and manage your well-being from a single, beautifully designed dashboard.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-bold text-white bg-primary rounded-xl hover:bg-secondary transition-all shadow-lg hover:shadow-primary/50 transform hover:-translate-y-1">
                Book Appointment
            </a>
            <a href="{{ route('patient.medical_history.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-bold text-navy bg-white border border-gray-200 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition-all">
                View Records
            </a>
        </div>
    </div>
</div>

{{-- Metrics Section --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Metric 1 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-primary mb-3 group-hover:scale-110 transition-transform">
            <i class="bi bi-calendar-event text-xl"></i>
        </div>
        <div class="text-4xl font-bold text-navy dark:text-white mb-1">{{ $upcomingAppointmentsCount ?? 0 }}</div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Upcoming Appointments</p>
    </div>
    <!-- Metric 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-success mb-3 group-hover:scale-110 transition-transform">
             <i class="bi bi-file-earmark-medical text-xl"></i>
        </div>
        <div class="text-4xl font-bold text-navy dark:text-white mb-1">{{ $medicalDocumentsCount ?? 0 }}</div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Medical Records</p>
    </div>
    <!-- Metric 3 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 rounded-full bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-500 mb-3 group-hover:scale-110 transition-transform">
            <i class="bi bi-person-check text-xl"></i>
        </div>
        <div class="text-4xl font-bold text-navy dark:text-white mb-1">{{ $profileCompletion ?? 0 }}%</div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Profile Completion</p>
    </div>
</div>

{{-- Quick Actions Section (Subtle) --}}
<div class="mb-8">
    <h3 class="text-xl font-bold text-navy dark:text-white mb-6 px-1">Quick Access</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Quick Action: Book Appointment -->
            <a href="{{ route('patient.appointments.create') }}" class="block p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:border-primary/30 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-primary transition-all group">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <i class="bi bi-calendar-plus text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold text-navy dark:text-white group-hover:text-primary transition-colors">Book Appointment</h5>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Schedule Now</span>
                    </div>
                </div>
            </a>
            
            <!-- Quick Action: My Appointments -->
            <a href="{{ route('patient.appointments.index') }}" class="block p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:border-success/30 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-success transition-all group">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-lg bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-success group-hover:scale-110 transition-transform">
                        <i class="bi bi-calendar-check text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold text-navy dark:text-white group-hover:text-success transition-colors">My Appointments</h5>
                         <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Check Status</span>
                    </div>
                </div>
            </a>

            <!-- Quick Action: Medical History -->
            <a href="{{ route('patient.medical_history.index') }}" class="block p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:border-warning/30 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-warning transition-all group">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center text-warning group-hover:scale-110 transition-transform">
                        <i class="bi bi-file-medical text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="text-lg font-bold text-navy dark:text-white group-hover:text-warning transition-colors">Medical History</h5>
                         <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">View Records</span>
                    </div>
                </div>
            </a>
        </div>
</div>

{{-- Advertisement Section (SmartMed Banner) --}}
@if(isset($ads))
@php
    $healthTipAd = $ads->where('type', 'health_tip')->first();
    $preventiveAd = $ads->where('type', 'preventive')->first();
    $doctorAd = $ads->where('type', 'service_promotion')->first() ?? $ads->where('type', 'doctor_highlight')->first();
@endphp
<div class="mb-12">
    <div class="flex items-center justify-between mb-6 px-1">
        <h3 class="text-xl font-bold text-navy dark:text-white">Highlights For You</h3>
    </div>

    {{-- Single Horizontal Layout Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden relative">
        
        {{-- Decorative Top Line --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-teal-400 via-blue-500 to-primary"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-gray-700">
            
            {{-- Column 1: HEALTH TIP --}}
            <div class="p-8 group hover:bg-teal-50/30 dark:hover:bg-teal-900/10 transition-colors duration-300 relative">
                <div class="flex flex-col h-full items-start">
                    <div class="flex items-center justify-between w-full mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-teal-600 bg-teal-100 dark:bg-teal-900/30 dark:text-teal-400">
                            Health Tip
                        </span>
                        @if(!($healthTipAd && $healthTipAd->image_path))
                        <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-900/20 flex items-center justify-center text-teal-500">
                            <i class="bi bi-droplet text-xl"></i>
                        </div>
                        @endif
                    </div>

                    @if($healthTipAd && $healthTipAd->image_path)
                    <div class="w-full h-40 rounded-xl overflow-hidden mb-4 shadow-sm">
                        <img src="{{ asset('storage/' . $healthTipAd->image_path) }}" alt="Health Tip" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @endif
                    
                    <h4 class="text-xl font-bold text-navy dark:text-white mb-3 group-hover:text-teal-600 transition-colors">
                        {{ $healthTipAd ? $healthTipAd->title : 'Welcome to SmartMed' }}
                    </h4>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6 flex-grow">
                        {{ $healthTipAd ? $healthTipAd->content : 'Stay hydrated and track your health regularly. We are here to help you live a healthier life.' }}
                    </p>
                    
                    <a href="{{ $healthTipAd && $healthTipAd->cta_link ? $healthTipAd->cta_link : '#' }}" class="inline-flex items-center text-sm font-bold text-teal-600 hover:text-teal-700 transition-colors group-hover:translate-x-1 duration-300">
                        Learn More <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            {{-- Column 2: PREVENTIVE --}}
            <div class="p-8 group hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors duration-300 relative">
                 <div class="flex flex-col h-full items-start">
                    <div class="flex items-center justify-between w-full mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-blue-600 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400">
                            Preventive
                        </span>
                        @if(!($preventiveAd && $preventiveAd->image_path))
                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500">
                            <i class="bi bi-calendar-check text-xl"></i>
                        </div>
                        @endif
                    </div>

                    @if($preventiveAd && $preventiveAd->image_path)
                    <div class="w-full h-40 rounded-xl overflow-hidden mb-4 shadow-sm">
                         <img src="{{ asset('storage/' . $preventiveAd->image_path) }}" alt="Preventive" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @endif
                    
                    <h4 class="text-xl font-bold text-navy dark:text-white mb-3 group-hover:text-blue-600 transition-colors">
                        {{ $preventiveAd ? $preventiveAd->title : 'Annual Health Screening Promo' }}
                    </h4>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6 flex-grow">
                         {{ $preventiveAd ? $preventiveAd->content : 'Early detection saves 20% off this month.' }}
                    </p>
                    
                    <a href="{{ $preventiveAd && $preventiveAd->cta_link ? $preventiveAd->cta_link : '#' }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors group-hover:translate-x-1 duration-300">
                        Learn More <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            {{-- Column 3: DOCTOR HIGHLIGHT --}}
            <div class="p-8 group hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors duration-300 relative">
                 <div class="flex flex-col h-full items-start">
                    <div class="flex items-center justify-between w-full mb-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-primary bg-primary/10 dark:bg-primary/20 dark:text-primary-light">
                            Doctor Highlight
                        </span>
                        @if(!($doctorAd && $doctorAd->image_path))
                        <div class="w-10 h-10 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary">
                            <i class="bi bi-person-heart text-xl"></i>
                        </div>
                        @endif
                    </div>

                    @if($doctorAd && $doctorAd->image_path)
                    <div class="w-full h-40 rounded-xl overflow-hidden mb-4 shadow-sm">
                        <img src="{{ asset('storage/' . $doctorAd->image_path) }}" alt="Doctor Highlight" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @endif
                    
                    <h4 class="text-xl font-bold text-navy dark:text-white mb-3 group-hover:text-primary transition-colors">
                        {{ $doctorAd ? $doctorAd->title : 'Introduce Our FrontLiner' }}
                    </h4>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-6 flex-grow">
                         {{ $doctorAd ? $doctorAd->content : 'Choose your preferred doctors.' }}
                    </p>
                    
                    <a href="{{ $doctorAd && $doctorAd->cta_link ? $doctorAd->cta_link : '#' }}" class="inline-flex items-center text-sm font-bold text-primary hover:text-secondary transition-colors group-hover:translate-x-1 duration-300">
                        Learn More <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

@endsection