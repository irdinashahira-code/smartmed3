@extends('layouts.doctor_nextkit')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1 justify-center">
        <div class="max-w-4xl mx-auto w-full">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ __('My Doctor Profile') }}</span>
                    <a href="{{ route('doctor.profile.edit') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit Profile</a>
                </div>

                <div class="p-6">
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($user->profile_photo_path)
                        <div class="text-center mb-6">
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="rounded-full border-4 border-white shadow-lg mx-auto object-cover" style="width: 150px; height: 150px;">
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Name</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->name }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Email</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->email }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Specialization</div>
                        <div class="md:col-span-2">
                            @if($user->specialization)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $user->specialization }}</span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400 italic">Not specified</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Qualification</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->qualification ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Bio</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->bio ?? '-' }}</div>
                    </div>

                    <hr class="h-px my-6 bg-gray-200 border-0 dark:bg-gray-700">

                    <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Personal Details</h5>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">IC Number</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->ic_number ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Phone Number</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->phone_number ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Gender</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ ucfirst($user->gender) ?? '-' }}</div>
                    </div>

                    <hr class="h-px my-6 bg-gray-200 border-0 dark:bg-gray-700">

                    <h5 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Clinic / Contact Address</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Address</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->address ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">City</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->city ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">State</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->state ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="font-bold text-gray-700 dark:text-gray-300">Postcode</div>
                        <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $user->postcode ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
