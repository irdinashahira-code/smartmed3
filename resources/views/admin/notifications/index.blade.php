@extends('layouts.admin_nextkit')

@section('title', 'Telegram Notification Dashboard')

@section('content')
<div class="w-full">
    <div class="mb-6">
        <h5 class="text-2xl font-bold text-gray-900 dark:text-white">Telegram Notification Dashboard</h5>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Sent -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Total Sent</h6>
            <h4 class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalSent }}</h4>
            <span class="text-xs text-gray-500">Messages delivered</span>
        </div>

        <!-- Total Failed -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Total Failed</h6>
            <h4 class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $totalFailed }}</h4>
            <span class="text-xs text-gray-500">Delivery errors</span>
        </div>

        <!-- Sent Today -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-400">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Sent Today</h6>
            <h4 class="text-2xl font-bold text-blue-500">{{ $todaySent }}</h4>
            <span class="text-xs text-gray-500">Delivered today</span>
        </div>

        <!-- Failed Today -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-400">
            <h6 class="text-xs font-bold text-gray-500 uppercase mb-2">Failed Today</h6>
            <h4 class="text-2xl font-bold text-yellow-500">{{ $todayFailed }}</h4>
            <span class="text-xs text-gray-500">Errors today</span>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h6 class="font-bold text-gray-900 dark:text-white">Notification Log</h6>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">Type</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Attempts</th>
                            <th scope="col" class="px-6 py-3">Sent At</th>
                            <th scope="col" class="px-6 py-3">Created At</th>
                            <th scope="col" class="px-6 py-3">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $notification->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $notification->user->name }}</div>
                                <small class="text-gray-500">{{ $notification->user->email }}</small>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $notification->type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($notification->status == 'sent')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Sent</span>
                                @elseif(str_contains($notification->status, 'failed'))
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ $notification->status }}</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{{ $notification->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $notification->attempt_count }}</td>
                            <td class="px-6 py-4">{{ $notification->sent_at }}</td>
                            <td class="px-6 py-4">{{ $notification->created_at }}</td>
                            <td class="px-6 py-4 text-red-500 text-xs">{{ Str::limit($notification->error_message, 50) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $notifications->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
