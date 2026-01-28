@extends('layouts.patient_nextkit')

@section('title', 'Appointment Summary')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Appointment Summary</h1>
        </div>
    </div>
</div>

<div class="flex flex-col bg-white dark:bg-gray-800 rounded-b-lg shadow-md p-6">
    <div class="mb-6">
        <h5 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Please review your appointment details:</h5>
        <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="font-medium text-gray-500 dark:text-gray-400">Patient Name</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $data['name'] ?? Auth::user()->name }}</div>

            <div class="font-medium text-gray-500 dark:text-gray-400">IC Number</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $data['ic_number'] ?? Auth::user()->ic_number }}</div>

            <div class="font-medium text-gray-500 dark:text-gray-400">Date</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($data['date'])->format('d M Y') }}</div>

            <div class="font-medium text-gray-500 dark:text-gray-400">Time</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($data['time'])->format('h:i A') }}</div>

            <div class="font-medium text-gray-500 dark:text-gray-400">Doctor</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">
                @if($doctor)
                    {{ $doctor->name }} ({{ $doctor->specialization }})
                @else
                    Any Available Doctor
                @endif
            </div>

            @if(!empty($data['weight']))
            <div class="font-medium text-gray-500 dark:text-gray-400">Weight</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $data['weight'] }} kg</div>
            @endif

            @if(!empty($data['type']))
            <div class="font-medium text-gray-500 dark:text-gray-400">Type</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ ucfirst($data['type']) }}</div>
            @endif

            @if(!empty($data['reason']))
            <div class="font-medium text-gray-500 dark:text-gray-400">Reason</div>
            <div class="md:col-span-2 text-gray-900 dark:text-white">{{ $data['reason'] }}</div>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('patient.appointments.store') }}">
        @csrf
        <input type="hidden" name="name" value="{{ $data['name'] ?? Auth::user()->name }}">
        <input type="hidden" name="ic_number" value="{{ $data['ic_number'] ?? Auth::user()->ic_number }}">
        <input type="hidden" name="date" value="{{ $data['date'] }}">
        <input type="hidden" name="time" value="{{ $data['time'] }}">
        <input type="hidden" name="doctor_id" value="{{ $data['doctor_id'] }}">
        <input type="hidden" name="weight" value="{{ $data['weight'] ?? '' }}">
        <input type="hidden" name="type" value="{{ $data['type'] ?? '' }}">
        <input type="hidden" name="reason" value="{{ $data['reason'] ?? '' }}">

        <div class="flex items-center justify-between mt-6">
            <a href="javascript:history.back()" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Edit Details</a>
            <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Proceed to Payment</button>
        </div>
    </form>
</div>
@endsection
