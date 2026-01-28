@extends('layouts.admin_nextkit')

@section('title', 'Advertisements')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Advertisement Management</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage patient dashboard advertisements and campaigns.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.advertisements.analytics') }}" class="btn bg-info text-white hover:bg-blue-600 px-4 py-2 rounded-lg flex items-center">
            <i class="bi bi-graph-up me-2"></i> Analytics
        </a>
        <a href="{{ route('admin.advertisements.create') }}" class="btn bg-primary text-white hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
            <i class="bi bi-plus-lg me-2"></i> Create Campaign
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Campaign Info</th>
                    <th scope="col" class="px-6 py-3">Type</th>
                    <th scope="col" class="px-6 py-3">Targeting</th>
                    <th scope="col" class="px-6 py-3">Duration</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Priority</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advertisements as $ad)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $ad->title }}</div>
                        @if($ad->image_path)
                            <img src="{{ asset('storage/' . $ad->image_path) }}" alt="Ad Image" class="h-8 w-auto mt-1 rounded">
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($ad->type == 'preventive') bg-blue-100 text-blue-800
                            @elseif($ad->type == 'health_tip') bg-green-100 text-green-800
                            @elseif($ad->type == 'service_promotion') bg-purple-100 text-purple-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $ad->type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1 text-xs">
                            <span>Age: {{ $ad->target_age_min ?? 0 }} - {{ $ad->target_age_max ?? 'Any' }}</span>
                            <span>Gender: {{ ucfirst($ad->target_gender) }}</span>
                            @if($ad->target_conditions)
                                <span class="text-gray-500 truncate max-w-[150px]" title="{{ implode(', ', $ad->target_conditions) }}">
                                    Cond: {{ count($ad->target_conditions) }} selected
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        {{ $ad->start_date->format('M d, Y') }} <br>
                        <span class="text-xs text-gray-500">to</span> <br>
                        {{ $ad->end_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($ad->is_active && $ad->end_date >= now() && $ad->start_date <= now())
                            <span class="flex items-center text-green-500">
                                <span class="w-2 h-2 rounded-full bg-green-500 me-2"></span> Active
                            </span>
                        @elseif(!$ad->is_active)
                            <span class="flex items-center text-red-500">
                                <span class="w-2 h-2 rounded-full bg-red-500 me-2"></span> Inactive
                            </span>
                        @else
                            <span class="flex items-center text-gray-500">
                                <span class="w-2 h-2 rounded-full bg-gray-500 me-2"></span> Scheduled/Expired
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @for($i = 0; $i < $ad->priority; $i++)
                                <i class="bi bi-star-fill text-yellow-400 text-xs"></i>
                            @endfor
                            <span class="ml-1 text-xs">({{ $ad->priority }})</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.advertisements.edit', $ad->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('admin.advertisements.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No advertisements found. <a href="{{ route('admin.advertisements.create') }}" class="text-primary hover:underline">Create one now</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">
        {{ $advertisements->links() }}
    </div>
</div>
@endsection
