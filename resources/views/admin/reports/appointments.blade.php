@extends('layouts.admin_nextkit')

@section('title', 'Appointment Statistics')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left me-2"></i> Back to Reports
    </a>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-primary rounded-lg shadow-md p-6 text-white">
        <h6 class="text-sm font-medium opacity-80 mb-2">Total Appointments</h6>
        <h2 class="text-3xl font-bold">{{ $totalAppointments }}</h2>
    </div>
    <div class="bg-green-500 rounded-lg shadow-md p-6 text-white">
        <h6 class="text-sm font-medium opacity-80 mb-2">Completed</h6>
        <h2 class="text-3xl font-bold">{{ $completedAppointments }}</h2>
    </div>
    <div class="bg-red-500 rounded-lg shadow-md p-6 text-white">
        <h6 class="text-sm font-medium opacity-80 mb-2">Cancelled / Rejected</h6>
        <h2 class="text-3xl font-bold">{{ $cancelledAppointments }}</h2>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Status Breakdown -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Appointments by Status</h5>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3 text-right">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statsByStatus as $stat)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75">
                            <td class="px-4 py-3">
                                <span class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">{{ ucfirst($stat->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white">{{ $stat->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly Trend -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Trend (Last 12 Months)</h5>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Month</th>
                            <th scope="col" class="px-4 py-3 text-right">Total Appointments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyStats as $stat)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y') }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white">{{ $stat->total }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
