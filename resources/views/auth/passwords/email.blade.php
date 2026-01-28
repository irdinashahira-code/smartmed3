@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-xl')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-6">{{ __('Reset Password') }}</h2>

    @if (session('status'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            {{ __('Send Password Reset Link') }}
        </button>
        
        <!-- Back to Login -->
        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                Back to Login
            </a>
        </div>
    </form>
</div>
@endsection
