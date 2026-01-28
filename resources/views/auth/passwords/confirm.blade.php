@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-xl')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-6">{{ __('Confirm Password') }}</h2>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Password') }}</label>
            <input id="password" type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
            
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            {{ __('Confirm Password') }}
        </button>

        @if (Route::has('password.request'))
            <div class="text-center mt-4">
                <a class="text-sm text-primary-600 hover:underline dark:text-primary-500" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        @endif
    </form>
</div>
@endsection
