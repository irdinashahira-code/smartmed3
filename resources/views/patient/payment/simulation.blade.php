@extends('layouts.patient_nextkit')

@section('title', 'Payment Simulation')

@section('content')
<div class="grid grid-cols-1 justify-center">
    <div class="max-w-3xl mx-auto w-full">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Payment Simulation') }}</h5>
            </div>

            <div class="p-6">
                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                        <span class="font-medium">Success!</span> {{ session('success') }}
                    </div>
                @endif

                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                    <i class="bi bi-credit-card me-2 text-lg"></i>
                    <span class="sr-only">Info</span>
                    <div>
                        You will be redirected to Stripe Secure Payment Gateway (Test Mode).
                    </div>
                </div>

                <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Booking Details</h5>
                <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="font-medium text-gray-500 dark:text-gray-400">Patient Name</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Date</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Time</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Doctor</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">
                        @if($appointment->doctor)
                            {{ $appointment->doctor->name }}
                        @else
                            Any Available Doctor
                        @endif
                    </div>
                    
                    <div class="font-medium text-gray-500 dark:text-gray-400">Total Amount</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white font-bold">RM 2.00</div>
                </div>

                <form method="POST" action="{{ route('payment.initiate', $appointment->id) }}">
                    @csrf
                    <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6">
                        <a href="{{ route('patient.appointments.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 text-center">Cancel Payment</a>
                        <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full sm:w-auto text-center">Proceed to Payment (RM 2.00)</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
