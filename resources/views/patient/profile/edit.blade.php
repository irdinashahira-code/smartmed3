@extends('layouts.patient_nextkit')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ __('Edit Profile') }}</h1>
        </div>
    </div>
</div>

<div class="p-4">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="p-6">
                <form method="POST" action="{{ route('patient.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 border-b pb-2 dark:border-gray-700">Personal Information</h3>

                    <div class="mb-6 flex justify-center">
                        <div class="text-center">
                            <div class="relative inline-block group cursor-pointer" onclick="document.getElementById('profile_photo').click()">
                                
                                <!-- Avatar Image / Placeholder -->
                                <div id="avatar-preview-container" class="rounded-full overflow-hidden shadow-sm w-36 h-36 bg-gray-100 dark:bg-gray-700">
                                    @if($user->profile_photo_path)
                                        <img id="avatar-preview" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                                    @else
                                        <div id="avatar-placeholder" class="w-full h-full flex items-center justify-center bg-gray-400 text-white">
                                            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <img id="avatar-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>

                                <!-- Overlay (WhatsApp Style) -->
                                <div class="absolute inset-0 rounded-full flex flex-col items-center justify-center text-white bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-xs font-bold px-2">Change Photo</span>
                                </div>
                            </div>

                            <!-- Hidden File Input -->
                            <input id="profile_photo" type="file" class="hidden @error('profile_photo') is-invalid @enderror" name="profile_photo" accept="image/*" onchange="previewImage(this)">
                            
                            @error('profile_photo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Name') }}</label>
                            <input id="name" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('email') border-red-500 @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ic_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('IC Number') }}</label>
                            <input id="ic_number" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('ic_number') border-red-500 @enderror" name="ic_number" value="{{ old('ic_number', $user->ic_number) }}">
                            @error('ic_number')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Phone Number') }}</label>
                            <input id="phone_number" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('phone_number') border-red-500 @enderror" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                            @error('phone_number')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Date of Birth') }}</label>
                            <input id="date_of_birth" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('date_of_birth') border-red-500 @enderror" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            @error('date_of_birth')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Age') }}</label>
                            <input id="age" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('age') border-red-500 @enderror" name="age" value="{{ old('age', $user->age) }}" min="0">
                            @error('age')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Gender') }}</label>
                            <select id="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('gender') border-red-500 @enderror" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 border-b pb-2 dark:border-gray-700 mt-8">Address</h3>

                    <div class="mb-6">
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Address') }}</label>
                        <textarea id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('address') border-red-500 @enderror" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('City') }}</label>
                            <input id="city" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('city') border-red-500 @enderror" name="city" value="{{ old('city', $user->city) }}">
                            @error('city')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="state" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('State') }}</label>
                            <input id="state" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('state') border-red-500 @enderror" name="state" value="{{ old('state', $user->state) }}">
                            @error('state')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postcode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Postcode') }}</label>
                            <input id="postcode" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('postcode') border-red-500 @enderror" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                            @error('postcode')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 border-b pb-2 dark:border-gray-700 mt-8">Emergency Contact</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="emergency_contact_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Contact Name') }}</label>
                            <input id="emergency_contact_name" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('emergency_contact_name') border-red-500 @enderror" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}">
                            @error('emergency_contact_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Contact Phone') }}</label>
                            <input id="emergency_contact_phone" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('emergency_contact_phone') border-red-500 @enderror" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}">
                            @error('emergency_contact_phone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_relationship" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Relationship') }}</label>
                            <input id="emergency_contact_relationship" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('emergency_contact_relationship') border-red-500 @enderror" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $user->emergency_contact_relationship) }}">
                            @error('emergency_contact_relationship')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <a href="{{ route('patient.profile.show') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            {{ __('Update Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'profile_photo') {
            previewImage(e.target);
        }
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
