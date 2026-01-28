@extends('layouts.auth_nextkit')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 mb-4">
    <h2 class="text-2xl font-bold text-center text-primary mb-6 dark:text-white">Log In</h2>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
            <input id="email" type="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@company.com">
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
            <input id="password" type="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full text-white bg-primary hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mb-4">
            {{ __('Log In') }}
        </button>

        <div class="flex items-center justify-between mb-4">
            <div class="h-px bg-gray-300 flex-grow dark:bg-gray-600"></div>
            <span class="px-3 text-gray-500 text-sm font-medium dark:text-gray-400">OR</span>
            <div class="h-px bg-gray-300 flex-grow dark:bg-gray-600"></div>
        </div>

        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center text-gray-900 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-700 mb-4">
            <i class="bi bi-google me-2 text-red-500"></i> Continue with Google
        </a>

        @if (Route::has('password.request'))
            <div class="text-center">
                <a class="text-sm text-primary hover:underline dark:text-blue-500" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            </div>
        @endif
    </form>
</div>

<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 text-center">
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-primary font-bold hover:underline dark:text-blue-500">Sign up</a>
    </p>
</div>
@endsection
