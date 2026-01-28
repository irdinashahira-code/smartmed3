@extends('layouts.doctor_nextkit')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1 justify-center">
        <div class="max-w-4xl mx-auto w-full">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-primary text-white font-bold">
                    {{ __('Edit Doctor Profile') }}
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="flex justify-center mb-6">
                            <div class="text-center">
                                <div class="relative inline-block group cursor-pointer" onclick="document.getElementById('profile_photo').click()">
                                    
                                    <!-- Avatar Image / Placeholder -->
                                    <div id="avatar-preview-container" class="rounded-full overflow-hidden shadow-sm w-36 h-36 bg-gray-100 dark:bg-gray-700">
                                        @if($user->profile_photo_path)
                                            <img id="avatar-preview" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                                        @else
                                            <div id="avatar-placeholder" class="w-full h-full flex items-center justify-center bg-gray-400 text-white">
                                                <i class="bi bi-person-fill text-6xl"></i>
                                            </div>
                                            <img id="avatar-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                        @endif
                                    </div>

                                    <!-- Overlay (WhatsApp Style) -->
                                    <div class="absolute inset-0 rounded-full flex flex-col items-center justify-center text-white bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <i class="bi bi-camera-fill text-2xl mb-1"></i>
                                        <span class="text-xs font-bold text-center px-2">Change Profile Photo</span>
                                    </div>
                                </div>

                                <!-- Hidden File Input -->
                                <input id="profile_photo" type="file" class="hidden" name="profile_photo" accept="image/*" onchange="previewImage(this)">
                                
                                @error('profile_photo')
                                    <div class="text-red-600 dark:text-red-400 mt-2 text-sm">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Name') }}</label>
                            <div class="md:col-span-2">
                                <input id="name" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Email Address') }}</label>
                            <div class="md:col-span-2">
                                <input id="email" type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <h6 class="mb-6 text-center text-primary font-bold text-lg">Professional Information</h6>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="specialization" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Specialization') }}</label>
                            <div class="md:col-span-2">
                                <input id="specialization" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="specialization" value="{{ old('specialization', $user->specialization) }}" placeholder="e.g. Cardiologist, General Practitioner">
                                @error('specialization')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="qualification" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Qualification') }}</label>
                            <div class="md:col-span-2">
                                <input id="qualification" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="qualification" value="{{ old('qualification', $user->qualification) }}" placeholder="e.g. MBBS, MD">
                                @error('qualification')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="bio" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Bio / Description') }}</label>
                            <div class="md:col-span-2">
                                <textarea id="bio" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <h6 class="mb-6 text-center text-primary font-bold text-lg">Personal Details</h6>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="ic_number" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('IC Number') }}</label>
                            <div class="md:col-span-2">
                                <input id="ic_number" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="ic_number" value="{{ old('ic_number', $user->ic_number) }}">
                                @error('ic_number')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="phone_number" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Phone Number') }}</label>
                            <div class="md:col-span-2">
                                <input id="phone_number" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                                @error('phone_number')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="gender" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Gender') }}</label>
                            <div class="md:col-span-2">
                                <select id="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="gender">
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                </select>
                                @error('gender')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        <h6 class="mb-6 text-center text-primary font-bold text-lg">Contact Address</h6>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Address') }}</label>
                            <div class="md:col-span-2">
                                <textarea id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="city" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('City') }}</label>
                            <div class="md:col-span-2">
                                <input id="city" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="city" value="{{ old('city', $user->city) }}">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="state" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('State') }}</label>
                            <div class="md:col-span-2">
                                <input id="state" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="state" value="{{ old('state', $user->state) }}">
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <label for="postcode" class="block text-sm font-medium text-gray-900 dark:text-white md:text-right md:pt-2">{{ __('Postcode') }}</label>
                            <div class="md:col-span-2">
                                <input id="postcode" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                                @error('postcode')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-8">
                             <a href="{{ route('doctor.profile.show') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
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
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                // Hide placeholder and show preview
                const placeholder = document.getElementById('avatar-placeholder');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                    placeholder.classList.remove('flex');
                }
                
                var preview = document.getElementById('avatar-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
