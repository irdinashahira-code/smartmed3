@extends('layouts.admin_nextkit')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Patients Card -->
    <div class="block max-w-sm p-6 bg-primary border border-gray-200 rounded-lg shadow hover:bg-blue-800 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 text-white relative overflow-hidden group">
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-bold tracking-tight text-white dark:text-white">Total Patients</h5>
                <i class="bi bi-people text-4xl opacity-50"></i>
            </div>
            <h2 class="text-4xl font-extrabold mb-2">{{ $totalPatients }}</h2>
            <a href="{{ route('admin.patients') }}" class="inline-flex items-center text-sm font-medium hover:underline">
                View Details
                <svg class="w-3 h-3 ms-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </a>
        </div>
        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    </div>

    <!-- Doctors Card -->
    <div class="block max-w-sm p-6 bg-success border border-gray-200 rounded-lg shadow hover:bg-green-600 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 text-white relative overflow-hidden group">
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-bold tracking-tight text-white dark:text-white">Total Doctors</h5>
                <i class="bi bi-person-badge text-4xl opacity-50"></i>
            </div>
            <h2 class="text-4xl font-extrabold mb-2">{{ $totalDoctors }}</h2>
            <a href="{{ route('admin.doctors') }}" class="inline-flex items-center text-sm font-medium hover:underline">
                View Details
                <svg class="w-3 h-3 ms-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </a>
        </div>
        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    </div>

    <!-- Pending Approvals Card -->
    <div class="block max-w-sm p-6 bg-warning border border-gray-200 rounded-lg shadow hover:bg-yellow-500 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 text-white relative overflow-hidden group">
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-xl font-bold tracking-tight text-white dark:text-white">Pending Approvals</h5>
                <i class="bi bi-exclamation-circle text-4xl opacity-50"></i>
            </div>
            <h2 class="text-4xl font-extrabold mb-2">{{ $pendingDoctors }}</h2>
            <a href="{{ route('admin.doctors') }}" class="inline-flex items-center text-sm font-medium hover:underline">
                Review Now
                <svg class="w-3 h-3 ms-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </a>
        </div>
        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Rated Doctors -->
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-row items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-bold leading-none text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-trophy text-success mr-2"></i> Top Rated Doctors
            </h5>
        </div>
        <div class="flow-root p-4">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topRatedDoctors as $doctor)
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($doctor->profile_photo_path)
                                    <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/' . $doctor->profile_photo_path) }}" alt="{{ $doctor->name }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-lightprimary text-primary flex items-center justify-center font-bold text-lg">
                                        {{ substr($doctor->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $doctor->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    {{ $doctor->specialization }}
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                <span class="text-warning flex items-center">
                                    {{ number_format($doctor->feedbacks_avg_rating, 1) }} <i class="bi bi-star-fill ms-1"></i>
                                </span>
                                <span class="text-xs text-muted ml-2 font-normal">{{ $doctor->feedbacks_count }} reviews</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-3 sm:py-4 text-center text-gray-500">No ratings available yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Lowest Rated Doctors -->
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-row items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-bold leading-none text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-exclamation-triangle text-error mr-2"></i> Lowest Rated Doctors
            </h5>
        </div>
        <div class="flow-root p-4">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($lowestRatedDoctors as $doctor)
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($doctor->profile_photo_path)
                                    <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/' . $doctor->profile_photo_path) }}" alt="{{ $doctor->name }}">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-lighterror text-error flex items-center justify-center font-bold text-lg">
                                        {{ substr($doctor->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $doctor->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    {{ $doctor->specialization }}
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                <span class="text-warning flex items-center">
                                    {{ number_format($doctor->feedbacks_avg_rating, 1) }} <i class="bi bi-star-fill ms-1"></i>
                                </span>
                                <span class="text-xs text-muted ml-2 font-normal">{{ $doctor->feedbacks_count }} reviews</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-3 sm:py-4 text-center text-gray-500">No ratings available yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection