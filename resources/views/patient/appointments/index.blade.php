@extends('layouts.patient_nextkit')

@section('title', 'My Appointments')

@section('content')
<div class="grid grid-cols-1 gap-6">
    <!-- Queue Status Card (Real-time) -->
    <div id="queue-status-card" class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 border-primary" style="display: none;">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 rounded-t-lg flex items-center">
            <i class="bi bi-clock-history mr-2 text-primary text-xl"></i>
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Live Queue Status</h5>
        </div>
        <div class="p-6 text-center">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">Queue #<span id="q-number">-</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h5 class="text-sm font-medium text-gray-500 uppercase">Current Position</h5>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" id="q-position">-</p>
                </div>
                <div>
                    <h5 class="text-sm font-medium text-gray-500 uppercase">Est. Wait Time</h5>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><span id="q-wait">-</span> min</p>
                </div>
                <div>
                    <h5 class="text-sm font-medium text-gray-500 uppercase">Status</h5>
                    <span class="bg-yellow-100 text-yellow-800 text-xl font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300" id="q-status">-</span>
                </div>
            </div>
            <div class="mt-6 p-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                Currently Serving: <strong>Queue #<span id="q-serving">-</span></strong>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Upcoming Appointments') }}</h5>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('patient.dashboard') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Back to Dashboard</a>
                <a href="{{ route('patient.appointments.history') }}" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">History</a>
                <a href="{{ route('patient.appointments.create') }}" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Book New</a>
            </div>
        </div>

        <div class="p-0">
            @if($appointments->isEmpty())
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    You have no upcoming appointments.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Time</th>
                                <th scope="col" class="px-6 py-3">Doctor</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td class="px-6 py-4">{{ $appointment->doctor ? $appointment->doctor->name : 'Any Available Doctor' }}</td>
                                <td class="px-6 py-4">{{ ucfirst($appointment->type ?? '-') }}</td>
                                <td class="px-6 py-4">
                                    @if($appointment->status == 'pending_payment')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending Payment</span>
                                        <a href="{{ route('patient.appointments.payment', $appointment->id) }}" class="inline-block mt-1 text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Pay Now</a>
                                    @elseif($appointment->status == 'paid')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Paid</span>
                                        <a href="{{ route('patient.appointments.receipt', $appointment->id) }}" class="inline-block mt-1 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Receipt</a>
                                    @else
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $appointment->status == 'booked' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($appointment->queue_number)
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Queue #{{ $appointment->queue_number }}</span>
                                        <br>
                                        <small class="text-gray-500">{{ ucfirst($appointment->queue_status) }}</small>
                                    @elseif(\Carbon\Carbon::parse($appointment->appointment_date)->isSameDay(\Carbon\Carbon::now('Asia/Kuala_Lumpur')) && in_array($appointment->status, ['paid', 'booked']))
                                        <form action="{{ route('patient.queue.checkin', $appointment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">Check In (Get Queue #)</button>
                                        </form>
                                    @elseif($appointment->cancellation_status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Cancellation Pending</span>
                                    @elseif($appointment->reschedule_status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Reschedule Pending</span>
                                    @elseif($appointment->reschedule_status === 'approved')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Rescheduled</span>
                                    @else
                                        @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                                            <div class="flex gap-2">
                                                <form action="{{ route('patient.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                    @csrf
                                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Cancel</button>
                                                </form>
                                                <a href="{{ route('patient.appointments.reschedule', $appointment->id) }}" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:focus:ring-yellow-900">Reschedule</a>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const queueCard = document.getElementById('queue-status-card');
        const qNumber = document.getElementById('q-number');
        const qPosition = document.getElementById('q-position');
        const qWait = document.getElementById('q-wait');
        const qStatus = document.getElementById('q-status');
        const qServing = document.getElementById('q-serving');

        function fetchQueueStatus() {
            fetch('{{ route("patient.queue.status") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.active) {
                        queueCard.style.display = 'block';
                        qNumber.innerText = data.queue_number;
                        qPosition.innerText = data.current_position;
                        qWait.innerText = data.estimated_wait;
                        qStatus.innerText = data.status;
                        qServing.innerText = data.current_serving;
                        
                        // Color coding status
                        if(data.status === 'Called') {
                            qStatus.className = 'bg-green-100 text-green-800 text-xl font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 blink-me'; 
                        } else {
                            qStatus.className = 'bg-yellow-100 text-yellow-800 text-xl font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300';
                        }
                    } else {
                        queueCard.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching queue status:', error));
        }

        // Poll every 3 seconds (Real-time updates)
        fetchQueueStatus();
        setInterval(fetchQueueStatus, 3000);
    });
</script>
<style>
    .blink-me {
        animation: blinker 1s linear infinite;
    }
    @keyframes blinker {
        50% { opacity: 0; }
    }
</style>
@endpush
@endsection
