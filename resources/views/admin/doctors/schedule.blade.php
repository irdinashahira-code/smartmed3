@extends('layouts.admin_nextkit')

@section('title', 'Manage Doctor Schedule')

@section('content')
<div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mb-6">
    <div>
        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Schedule: Dr. {{ $doctor->name }}</h4>
        <p class="text-gray-500 dark:text-gray-400">{{ $doctor->specialization }}</p>
    </div>
    <a href="{{ route('admin.doctors') }}" class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 flex items-center">
        <i class="bi bi-arrow-left me-2"></i> Back to Doctors
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Weekly Schedule -->
    <div class="md:col-span-2">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Weekly Availability</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.doctors.schedule.update', $doctor->id) }}" method="POST">
                    @csrf
                    
                    @foreach($weekDays as $index => $dayName)
                        @php
                            $schedule = $schedules->firstWhere('day_of_week', $index);
                            $isActive = $schedule ? $schedule->is_active : false;
                            $startTime = $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '08:00';
                            $endTime = $schedule ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '22:00';
                        @endphp
                        
                        <div class="flex flex-col md:flex-row items-center mb-4 pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0 last:mb-0 last:pb-0">
                            <div class="w-full md:w-1/4 mb-3 md:mb-0">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="schedules[{{ $index }}][is_active]" value="1" class="sr-only peer" id="active_{{ $index }}" {{ $isActive ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $dayName }}</span>
                                </label>
                                <input type="hidden" name="schedules[{{ $index }}][day_of_week]" value="{{ $index }}">
                            </div>
                            <div class="w-full md:w-3/4">
                                <div class="grid grid-cols-2 gap-4 items-center">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">From</span>
                                        <input type="time" name="schedules[{{ $index }}][start_time]" value="{{ $startTime }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">To</span>
                                        <input type="time" name="schedules[{{ $index }}][end_time]" value="{{ $endTime }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-6">
                        <button type="submit" class="w-full text-white bg-primary hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save Schedule Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaves / Unavailable Slots -->
    <div class="md:col-span-1">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Leaves</h5>
            </div>
            <div class="p-0">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leaves as $leave)
                        <li class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</div>
                                    <small class="text-gray-500 dark:text-gray-400 block">
                                        @if($leave->start_time)
                                            {{ \Carbon\Carbon::parse($leave->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($leave->end_time)->format('H:i') }}
                                        @else
                                            Full Day
                                        @endif
                                        @if($leave->reason)
                                            • {{ $leave->reason }}
                                        @endif
                                    </small>
                                </div>
                                <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-end gap-2 mt-3">
                                @if($leave->status === 'pending')
                                    <form action="{{ route('admin.doctors.leaves.approve', $leave->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 flex items-center">
                                            <i class="bi bi-check-lg me-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.doctors.leaves.reject', $leave->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 flex items-center">
                                            <i class="bi bi-x-lg me-1"></i> Reject
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.doctors.schedule.leave.delete', $leave->id) }}" method="POST" onsubmit="return confirm('Remove this leave permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 flex items-center">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="p-4 text-center text-gray-500 dark:text-gray-400">No upcoming leaves found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
