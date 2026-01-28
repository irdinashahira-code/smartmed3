@extends('layouts.auth_nextkit')

@section('auth_width', 'max-w-xl')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-6">{{ __('Verify Your Email Address') }}</h2>

    @if (session('resent'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }},
    </div>
    
    <form class="inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="font-medium text-primary-600 hover:underline dark:text-primary-500 p-0 m-0 border-0 bg-transparent cursor-pointer">{{ __('click here to request another') }}</button>.
    </form>
</div>
@endsection
