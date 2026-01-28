@extends('layouts.admin_nextkit')

@section('title', 'Ad Analytics')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
        <a href="{{ route('admin.advertisements.index') }}" class="hover:text-primary">Advertisements</a>
        <i class="bi bi-chevron-right text-xs"></i>
        <span>Analytics Dashboard</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Campaign Performance</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Impressions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-primary">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Impressions</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalImpressions) }}</h2>
            </div>
            <div class="p-2 bg-blue-50 dark:bg-blue-900 rounded-lg text-primary dark:text-blue-300">
                <i class="bi bi-eye text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Clicks -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-success">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Clicks</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalClicks) }}</h2>
            </div>
            <div class="p-2 bg-green-50 dark:bg-green-900 rounded-lg text-success dark:text-green-300">
                <i class="bi bi-mouse text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- CTR -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-warning">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Avg. CTR</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($ctr, 2) }}%</h2>
            </div>
            <div class="p-2 bg-yellow-50 dark:bg-yellow-900 rounded-lg text-warning dark:text-yellow-300">
                <i class="bi bi-percent text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Campaign Performance Breakdown</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Campaign</th>
                    <th scope="col" class="px-6 py-3 text-center">Impressions</th>
                    <th scope="col" class="px-6 py-3 text-center">Clicks</th>
                    <th scope="col" class="px-6 py-3 text-center">CTR</th>
                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adStats as $ad)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ $ad->title }}
                        <div class="text-xs text-gray-500 font-normal mt-1">{{ ucfirst(str_replace('_', ' ', $ad->type)) }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ number_format($ad->impressions) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ number_format($ad->clicks) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center">
                            <span class="font-bold {{ $ad->ctr > 5 ? 'text-green-500' : ($ad->ctr > 2 ? 'text-yellow-500' : 'text-red-500') }}">
                                {{ number_format($ad->ctr, 2) }}%
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($ad->is_active)
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Active</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Inactive</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No data available yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
