@extends('layouts.admin_nextkit')

@section('title', 'System Activity Logs')

@section('content')
<div class="w-full">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-bold text-gray-900 dark:text-white">Audit Trail / Activity Logs</h5>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Time</th>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                            <th scope="col" class="px-6 py-3">Module</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $log->user->name }}</div>
                                    <small class="text-gray-500">{{ $log->user->email }}</small>
                                @else
                                    <span class="text-red-500">System / Deleted User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->user)
                                    @php
                                        $roleColor = match($log->user->role) {
                                            'admin' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'doctor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            default => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                        };
                                    @endphp
                                    <span class="{{ $roleColor }} text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ ucfirst($log->user->role) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $actionColor = match($log->action) {
                                        'login' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
                                        'delete' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'update' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    };
                                @endphp
                                <span class="{{ $actionColor }} text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ ucfirst($log->module) }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ Str::limit($log->description, 50) }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No activity logs found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $logs->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
