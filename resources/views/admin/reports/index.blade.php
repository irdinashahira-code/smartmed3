@extends('layouts.admin_nextkit')

@section('title', 'Reports & Analytics')

@section('content')
<div class="mb-6">
    <h4 class="text-xl font-bold text-gray-900 dark:text-white">Overview</h4>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Monthly Revenue Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full flex flex-col">
        <div class="p-6 text-center flex-grow flex flex-col justify-center items-center">
            <div class="mb-4">
                <i class="bi bi-cash-coin text-green-500 text-6xl"></i>
            </div>
            <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Monthly Revenue</h5>
            <p class="text-gray-500 dark:text-gray-400 mb-6">View detailed breakdown of monthly earnings and financial performance.</p>
            <a href="{{ route('admin.reports.revenue') }}" class="mt-auto text-green-600 hover:text-white border border-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">
                View Revenue
            </a>
        </div>
    </div>

    <!-- E-Invoice / Transaction History Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full flex flex-col">
        <div class="p-6 text-center flex-grow flex flex-col justify-center items-center">
            <div class="mb-4">
                <i class="bi bi-receipt text-primary text-6xl"></i>
            </div>
            <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-2">E-Invoices</h5>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Access transaction history and view individual payment receipts.</p>
            <a href="{{ route('admin.reports.invoices') }}" class="mt-auto text-primary hover:text-white border border-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                View Invoices
            </a>
        </div>
    </div>

    <!-- Appointment Statistics Card -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden h-full flex flex-col">
        <div class="p-6 text-center flex-grow flex flex-col justify-center items-center">
            <div class="mb-4">
                <i class="bi bi-bar-chart-line text-cyan-500 text-6xl"></i>
            </div>
            <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Appointment Statistics</h5>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Analyze appointment volume, completion rates, and doctor workload.</p>
            <a href="{{ route('admin.reports.appointments') }}" class="mt-auto text-cyan-500 hover:text-white border border-cyan-500 hover:bg-cyan-600 focus:ring-4 focus:outline-none focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-cyan-400 dark:text-cyan-400 dark:hover:text-white dark:hover:bg-cyan-500 dark:focus:ring-cyan-900">
                View Statistics
            </a>
        </div>
    </div>
</div>
@endsection
