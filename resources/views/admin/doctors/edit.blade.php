@extends('layouts.admin_nextkit')

@section('title', 'Edit Doctor Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-6">
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
            <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Doctor: {{ $doctor->name }}</h5>
        </div>
        
        @if($doctor->profile_photo_path)
            <div class="text-center mb-6">
                <img src="{{ asset('storage/' . $doctor->profile_photo_path) }}" alt="Profile Photo" class="rounded-full w-32 h-32 object-cover mx-auto border-4 border-white dark:border-gray-700 shadow-lg">
            </div>
        @else
            <div class="text-center mb-6">
                <div class="w-32 h-32 rounded-full bg-lightprimary text-primary flex items-center justify-center font-bold text-5xl mx-auto border-4 border-white dark:border-gray-700 shadow-lg">
                    {{ substr($doctor->name, 0, 1) }}
                </div>
            </div>
        @endif

        <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $doctor->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $doctor->email) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Account Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('status') border-red-500 @enderror" required>
                    <option value="active" {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ old('status', $doctor->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ old('status', $doctor->status) == 'rejected' ? 'selected' : '' }}>Rejected/Inactive</option>
                </select>
                @error('status')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="specialization" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Specialization</label>
                <select id="specialization" name="specialization" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('specialization') border-red-500 @enderror" required>
                    <option value="">Select Specialization</option>
                    @foreach(['General Practitioner', 'Cardiologist', 'Dermatologist', 'Pediatrician', 'Neurologist'] as $spec)
                        <option value="{{ $spec }}" {{ old('specialization', $doctor->specialization) == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                    @endforeach
                </select>
                @error('specialization')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="qualification" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Qualification</label>
                <input type="text" name="qualification" id="qualification" value="{{ old('qualification', $doctor->qualification) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('qualification') border-red-500 @enderror">
                @error('qualification')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $doctor->phone_number) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('phone_number') border-red-500 @enderror">
                @error('phone_number')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bio</label>
                <textarea id="bio" name="bio" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('bio') border-red-500 @enderror">{{ old('bio', $doctor->bio) }}</textarea>
                @error('bio')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('admin.doctors') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</a>
                <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection