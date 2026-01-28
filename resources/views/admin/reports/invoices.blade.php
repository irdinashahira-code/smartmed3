@extends('layouts.admin_nextkit')

@section('title', 'E-Invoices / Transactions')

@section('content')
<div class="w-full">
    <div class="mb-6">
        <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-primary text-sm flex items-center">
            <i class="bi bi-arrow-left mr-1"></i> Back to Reports
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <h5 class="text-lg font-bold text-gray-900 dark:text-white">Transaction History</h5>
            <form action="{{ route('admin.reports.invoices') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                <input type="text" name="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search Receipt / Patient..." value="{{ request('search') }}">
                <input type="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ request('date') }}">
                <button type="submit" class="text-white bg-primary hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 w-full md:w-auto">Search</button>
                @if(request('search') || request('date'))
                    <a href="{{ route('admin.reports.invoices') }}" class="py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 w-full md:w-auto text-center">Reset</a>
                @endif
            </form>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Receipt #</th>
                            <th scope="col" class="px-6 py-3">Patient</th>
                            <th scope="col" class="px-6 py-3">Doctor</th>
                            <th scope="col" class="px-6 py-3">Amount (RM)</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $invoice->payment_date->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 border border-gray-500">{{ $invoice->receipt_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900 dark:text-white font-semibold">{{ $invoice->appointment->patient->name ?? 'Unknown' }}</div>
                                <small class="text-gray-500 dark:text-gray-400">{{ $invoice->appointment->patient->email ?? '' }}</small>
                            </td>
                            <td class="px-6 py-4">{{ $invoice->appointment->doctor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ number_format($invoice->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('patient.appointments.receipt', $invoice->appointment_id) }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    <i class="bi bi-eye mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No transactions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $invoices->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
