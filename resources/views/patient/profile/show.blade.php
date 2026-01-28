@extends('layouts.patient_nextkit')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ __('My Profile') }}</h1>
        </div>
    </div>
</div>

<div class="p-4">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Profile Information') }}</h3>
                <a href="{{ route('patient.profile.edit') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Edit Profile
                </a>
            </div>

            <div class="p-6 space-y-6">
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-300 dark:border-green-800" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if($user->profile_photo_path)
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg dark:border-gray-700">
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Personal Details</h4>
                    </div>
                    <div class="md:col-span-2 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white font-medium">{{ $user->name }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">IC Number</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->ic_number ?? '-' }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->phone_number ?? '-' }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->date_of_birth ?? '-' }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Age</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->age ?? '-' }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</div>
                            <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ ucfirst($user->gender ?? '-') }}</div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Address</h4>
                        </div>
                        <div class="md:col-span-2 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->address ?? '-' }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">City</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->city ?? '-' }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">State</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->state ?? '-' }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Postcode</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->postcode ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Emergency Contact</h4>
                        </div>
                        <div class="md:col-span-2 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->emergency_contact_name ?? '-' }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->emergency_contact_phone ?? '-' }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Relationship</div>
                                <div class="sm:col-span-2 text-sm text-gray-900 dark:text-white">{{ $user->emergency_contact_relationship ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
