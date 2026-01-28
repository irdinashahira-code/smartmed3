@extends('layouts.app_nextkit')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-2xl">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 font-bold text-gray-900 dark:text-white">
                {{ __('Dashboard') }}
            </div>

            <div class="p-6 text-gray-900 dark:text-gray-300">
                @if (session('status'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{ __('You are logged in!') }}
            </div>
        </div>
    </div>
</div>
@endsection
