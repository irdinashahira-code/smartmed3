@extends('layouts.doctor_nextkit')

@section('content')
    <div class="mb-6">
        <a href="{{ route('doctor.dashboard') }}" class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 inline-flex items-center">
            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-primary text-white p-4 font-bold flex items-center">
            <i class="bi bi-calendar-check me-2"></i> {{ __('Appointments for Today') }}
        </div>
        <div class="p-6">
            @if($todayAppointments->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 mb-0 italic">No appointments scheduled for today.</p>
            @else
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Patient</th>
                                <th scope="col" class="px-6 py-3">IC Number</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Time</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayAppointments as $appointment)
                            @php
                                $now = \Carbon\Carbon::now();
                                $apptDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
                                
                                // Status Logic
                                $statusLabel = 'Confirmed';
                                $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';

                                if ($appointment->status == 'cancelled') {
                                    $statusLabel = 'Cancelled';
                                    $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                } elseif ($appointment->cancellation_status == 'pending') {
                                    $statusLabel = 'Cancellation Pending';
                                    $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                } elseif ($now->gt($apptDateTime->copy()->addMinutes(30))) {
                                    $statusLabel = 'Completed';
                                    $badgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                } elseif ($now->gte($apptDateTime) && $now->lte($apptDateTime->copy()->addMinutes(30))) {
                                    $statusLabel = 'Ongoing';
                                    $badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                } else {
                                    $statusLabel = 'Confirmed';
                                    $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                }
                            @endphp
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $appointment->patient->name }}</td>
                                <td class="px-6 py-4">{{ $appointment->patient->ic_number }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ $appointment->appointment_time }}</td>
                                <td class="px-6 py-4">{{ $appointment->type }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $badgeClass }} text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{ $statusLabel }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection