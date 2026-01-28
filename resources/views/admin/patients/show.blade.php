@extends('layouts.admin_nextkit')

@section('title', 'Patient Profile')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full">
            <div class="p-6 text-center">
                <div class="mb-4">
                    @if($patient->profile_photo_path)
                        <img src="{{ asset('storage/' . $patient->profile_photo_path) }}" alt="Profile Photo" class="rounded-full w-24 h-24 object-cover mx-auto border-4 border-white dark:border-gray-700 shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-full bg-lightprimary text-primary flex items-center justify-center font-bold text-4xl mx-auto border-4 border-white dark:border-gray-700 shadow-lg">
                            {{ substr($patient->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $patient->name }}</h4>
                <p class="text-gray-500 dark:text-gray-400 mb-2">{{ $patient->email }}</p>
                <span class="{{ $patient->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ ucfirst($patient->status ?? 'active') }}
                </span>
                
                <hr class="my-6 border-gray-200 dark:border-gray-700">
                
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('admin.patients.medical-history', $patient->id) }}" class="text-primary hover:text-white border border-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-900 flex items-center justify-center">
                        <i class="bi bi-file-medical me-2"></i> View Medical History
                    </a>
                    
                    <form action="{{ route('admin.patients.toggle-status', $patient->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full {{ $patient->status === 'active' ? 'text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-yellow-300 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900' : 'text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-green-300 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800' }} focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center">
                            <i class="bi bi-{{ $patient->status === 'active' ? 'pause-circle' : 'play-circle' }} me-2"></i> 
                            {{ $patient->status === 'active' ? 'Deactivate Account' : 'Activate Account' }}
                        </button>
                    </form>

                    @if($patient->status === 'inactive')
                        <form action="{{ route('admin.patients.delete', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient? All their data will be permanently removed.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 flex items-center justify-center">
                                <i class="bi bi-trash me-2"></i> Delete Patient
                            </button>
                        </form>
                    @else
                        <button class="w-full text-gray-400 bg-gray-200 border border-gray-200 cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-700 dark:border-gray-600 dark:text-gray-500 flex items-center justify-center" disabled title="Deactivate patient first">
                            <i class="bi bi-trash me-2"></i> Delete Patient (Locked)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="md:col-span-2">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h5>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</div>
                    <div class="md:col-span-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->name }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">IC / Passport Number</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->ic_number ?? 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->phone_number ?? 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') : 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Age</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->age ?? 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ ucfirst($patient->gender) ?? 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->address ?? 'Not provided' }}</div>
                </div>
                
                <h5 class="text-md font-semibold text-gray-900 dark:text-white mt-6 mb-3 pt-4 border-t border-gray-200 dark:border-gray-700">Emergency Contact</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->emergency_contact_name ?? 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->emergency_contact_phone ?? 'Not provided' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Relationship</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->emergency_contact_relationship ?? 'Not provided' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration Date</div>
                    <div class="md:col-span-2 text-sm text-gray-900 dark:text-white">{{ $patient->created_at->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
