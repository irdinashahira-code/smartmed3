@extends('layouts.admin_nextkit')

@section('title', 'Revenue Analytics')

@section('content')
<div class="w-full">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-primary text-sm flex items-center mb-2">
                <i class="bi bi-arrow-left mr-1"></i> Back to Reports
            </a>
            <h4 class="text-2xl font-bold text-gray-900 dark:text-white">Revenue Analytics</h4>
        </div>
        <div>
            <a href="{{ route('admin.reports.revenue.export') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="bi bi-download mr-2"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-primary">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Total Revenue</h6>
            <h3 class="text-2xl font-bold text-primary dark:text-primary-400">RM {{ number_format($totalRevenue, 2) }}</h3>
            <small class="text-gray-500 dark:text-gray-400">All time earnings</small>
        </div>

        <!-- Transactions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-gray-500">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Transactions</h6>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</h3>
            <small class="text-gray-500 dark:text-gray-400">Completed payments</small>
        </div>

        <!-- Avg Transaction -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-400">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Avg. Transaction</h6>
            <h3 class="text-2xl font-bold text-blue-500">RM {{ number_format($averageTransaction, 2) }}</h3>
            <small class="text-gray-500 dark:text-gray-400">Per appointment</small>
        </div>

        <!-- Monthly Growth -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 {{ $growthPercentage >= 0 ? 'border-green-500' : 'border-red-500' }}">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Monthly Growth</h6>
            <div class="flex items-center">
                <h3 class="text-2xl font-bold {{ $growthPercentage >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ number_format(abs($growthPercentage), 1) }}%
                </h3>
                <i class="bi {{ $growthPercentage >= 0 ? 'bi-arrow-up-right text-green-500' : 'bi-arrow-down-right text-red-500' }} ml-2 text-xl"></i>
            </div>
            <small class="text-gray-500 dark:text-gray-400">Vs previous month</small>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h6 class="font-bold text-gray-900 dark:text-white">Revenue Trend (Last 12 Months)</h6>
            </div>
            <div class="p-6">
                <div class="relative h-[300px] w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions / Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col h-full">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h6 class="font-bold text-gray-900 dark:text-white">Recent Performance</h6>
            </div>
            <div class="flex-grow overflow-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Month</th>
                            <th scope="col" class="px-6 py-3 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenues->take(6) as $revenue)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $revenue->month)->format('M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $revenue->transaction_count }} transactions</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-gray-900 dark:text-white">RM {{ number_format($revenue->total_amount, 2) }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-700">
                <button class="text-primary hover:text-primary-800 text-sm font-medium focus:outline-none" type="button" data-accordion-target="#fullHistory" aria-expanded="false" aria-controls="fullHistory" onclick="document.getElementById('fullHistory').classList.toggle('hidden')">
                    View Full History
                </button>
            </div>
        </div>
    </div>

    <!-- Collapsible Full History Table -->
    <div id="fullHistory" class="hidden mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h6 class="font-bold text-gray-900 dark:text-white">Detailed Revenue History</h6>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Month</th>
                            <th scope="col" class="px-6 py-3 text-center">Transactions</th>
                            <th scope="col" class="px-6 py-3 text-right">Revenue (RM)</th>
                            <th scope="col" class="px-6 py-3 text-right">Avg/Trans (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenues as $revenue)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $revenue->month)->format('F Y') }}</td>
                            <td class="px-6 py-4 text-center">{{ $revenue->transaction_count }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">{{ number_format($revenue->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                {{ $revenue->transaction_count > 0 ? number_format($revenue->total_amount / $revenue->transaction_count, 2) : '0.00' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No revenue data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Prepare data (Reverse to show chronological order)
        const labels = {!! json_encode($revenues->pluck('month')->map(function($m) { return \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y'); })->reverse()->values()) !!};
        const data = {!! json_encode($revenues->pluck('total_amount')->reverse()->values()) !!};

        if (labels.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (RM)',
                        data: data,
                        backgroundColor: 'rgba(23, 80, 235, 0.2)', // Primary color with opacity
                        borderColor: 'rgba(23, 80, 235, 1)', // Primary color
                        borderWidth: 2,
                        borderRadius: 4,
                        barThickness: 'flex',
                        maxBarThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'RM ' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#e5e7eb' // gray-200
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'RM ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
             // Show message if no data
             ctx.font = "14px Arial";
             ctx.fillStyle = "gray";
             ctx.textAlign = "center";
             ctx.fillText("No data available to display chart", ctx.canvas.width/2, ctx.canvas.height/2);
        }
    });
</script>
@endsection