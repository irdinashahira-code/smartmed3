@extends('layouts.patient_nextkit')

@section('title', 'Payment Receipt')

@section('content')
<div class="grid grid-cols-1 justify-center">
    <div class="max-w-3xl mx-auto w-full">
        <div id="receipt-card" class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-green-600 rounded-t-lg">
                <h5 class="text-lg font-semibold text-white">{{ __('Payment Success') }}</h5>
            </div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-green-600 dark:text-green-500 mb-2">Payment Successful</h2>
                    <p class="text-gray-600 dark:text-gray-300">Thank you for your payment.</p>
                </div>

                @php
                    $botUsername = env('TELEGRAM_BOT_USERNAME', 'SmartMedBot');
                    $userId = Auth::id();
                    $signature = substr(md5($userId . config('app.key')), 0, 10);
                    $telegramLink = "https://t.me/{$botUsername}?start=link_{$userId}_{$signature}";
                @endphp

                @if(!Auth::user()->telegram_chat_id)
                    <div class="flex items-center justify-between p-4 mb-6 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 no-print">
                        <div class="flex items-center">
                            <i class="bi bi-telegram text-2xl mr-3"></i>
                            <div>
                                <strong class="block">Get Live Queue Updates</strong>
                                <span class="text-sm">Connect your Telegram to receive automatic notifications.</span>
                            </div>
                        </div>
                        <a href="{{ $telegramLink }}" target="_blank" class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center gap-2">
                            <i class="bi bi-telegram"></i> Connect Now
                        </a>
                    </div>
                @endif

                <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-4">E-Receipt</h5>
                <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                    <div class="font-medium text-gray-500 dark:text-gray-400">Receipt Number</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white font-mono">{{ $payment->receipt_number }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Patient Name</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $appointment->patient->name }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Appointment Date</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Appointment Time</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Doctor</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">
                        @if($appointment->doctor)
                            {{ $appointment->doctor->name }}
                        @else
                            Any Available Doctor
                        @endif
                    </div>
                    
                    <div class="font-medium text-gray-500 dark:text-gray-400">Payment Amount</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white font-bold">RM {{ number_format($payment->amount, 2) }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Payment Date</div>
                    <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $payment->payment_date->format('d M Y h:i A') }}</div>

                    <div class="font-medium text-gray-500 dark:text-gray-400">Status</div>
                    <div class="md:col-span-2">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">PAID</span>
                    </div>
                </div>

                <div class="flex justify-between mt-8 no-print">
                    <a href="{{ route('patient.dashboard') }}" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Back to Dashboard</a>
                    <button onclick="window.print()" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Download / Print Receipt</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #receipt-card, #receipt-card * {
            visibility: visible;
        }
        #receipt-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
