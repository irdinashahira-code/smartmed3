@extends('layouts.admin_nextkit')

@section('title', 'All Appointments')

@section('content')
<div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
        <div class="w-full md:w-auto">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">System-wide Appointments</h5>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <form action="{{ route('admin.appointments') }}" method="GET" class="flex flex-col md:flex-row gap-2 items-center">
                <div class="flex items-center w-full md:w-auto">
                    <label for="ic_number" class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap mr-2">IC Number:</label>
                    <input type="text" name="ic_number" id="ic_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter IC" value="{{ request('ic_number') }}">
                </div>
                <div class="flex items-center w-full md:w-auto ml-0 md:ml-2">
                    <label for="date" class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap mr-2">Date:</label>
                    <input type="date" name="date" id="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ request('date') }}">
                </div>
                <div class="flex items-center w-full md:w-auto ml-0 md:ml-2 gap-2">
                    <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Search
                    </button>
                    @if(request('date') || request('ic_number'))
                        <a href="{{ route('admin.appointments') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Date & Time</th>
                    <th scope="col" class="px-4 py-3">Patient</th>
                    <th scope="col" class="px-4 py-3">Doctor</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                    <th scope="col" class="px-4 py-3">Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75">
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</div>
                        <div class="text-primary dark:text-blue-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $appointment->patient->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->email }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $appointment->doctor->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->doctor->specialization }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($appointment->status == 'confirmed')
                            <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium px-2.5 py-0.5 rounded">Confirmed</span>
                        @elseif($appointment->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                        @elseif($appointment->status == 'cancelled')
                            <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium px-2.5 py-0.5 rounded">Cancelled</span>
                        @elseif($appointment->status == 'completed')
                            <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">{{ ucfirst($appointment->status) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $appointment->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No appointments found in the system.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
