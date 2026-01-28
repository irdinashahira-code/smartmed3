@extends('layouts.doctor_nextkit')

@section('content')
<div class="container mx-auto">
    
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('success') }}
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Weekly Schedule Configuration -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
                <div class="bg-primary text-white p-4 font-bold flex items-center">
                    <i class="bi bi-calendar-week me-2"></i> {{ __('Weekly Working Hours') }}
                </div>
                <div class="p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Set your standard working hours. Uncheck "Active" for days off (e.g. weekends, recurring days off).</p>
                    <form method="POST" action="{{ route('doctor.schedule.update') }}">
                        @csrf
                        <div class="relative overflow-x-auto mb-4">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3" style="width: 120px;">Day</th>
                                        <th scope="col" class="px-6 py-3" style="width: 100px;">Status</th>
                                        <th scope="col" class="px-6 py-3">Working Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($weekDays as $index => $day)
                                        @php
                                            $schedule = $schedules->firstWhere('day_of_week', $index);
                                            $isActive = $schedule && $schedule->is_active;
                                            $startTime = $schedule ? Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '08:00';
                                            $endTime = $schedule ? Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '22:00';
                                        @endphp
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $isActive ? '' : 'opacity-50' }}">
                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                {{ $day }}
                                                <input type="hidden" name="schedules[{{ $index }}][day_of_week]" value="{{ $index }}">
                                            </td>
                                            <td class="px-6 py-4">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="schedules[{{ $index }}][is_active]" value="1" {{ $isActive ? 'checked' : '' }} onchange="toggleRow(this)" class="sr-only peer">
                                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $isActive ? 'Active' : 'Off' }}</span>
                                                </label>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center space-x-2 {{ $isActive ? '' : 'opacity-50 pointer-events-none' }}">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">From</span>
                                                    <input type="time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="schedules[{{ $index }}][start_time]" value="{{ $startTime }}" {{ $isActive ? '' : 'readonly' }}>
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">To</span>
                                                    <input type="time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" name="schedules[{{ $index }}][end_time]" value="{{ $endTime }}" {{ $isActive ? '' : 'readonly' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="grid">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full"><i class="bi bi-save me-2"></i>{{ __('Save Weekly Schedule') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Leaves & Special Slots -->
        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
                <div class="bg-red-600 text-white p-4 font-bold flex items-center">
                    <i class="bi bi-calendar-x me-2"></i> {{ __('Leaves & Unavailable Slots') }}
                </div>
                <div class="p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Block out specific dates or time slots for emergencies, personal appointments, or holidays.</p>
                    
                    <form method="POST" action="{{ route('doctor.schedule.leave.add') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('End Date') }}</label>
                                <input type="date" name="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required min="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('Time Slot (Optional)') }}</label>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">From</span>
                                    <input type="time" name="start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">To</span>
                                    <input type="time" name="end_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                </div>
                                <small class="text-gray-500 dark:text-gray-400 block mt-1 text-xs">
                                    <i class="bi bi-info-circle"></i> Leave blank for Full Day Off. If times are set, you will be unavailable during these hours for the selected dates.
                                </small>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ __('Reason') }}</label>
                                <input type="text" name="reason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="e.g. Annual Leave, Dentist Appointment, Emergency" required>
                            </div>
                            
                            <div class="md:col-span-2 text-right">
                                <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"><i class="bi bi-plus-circle me-2"></i>{{ __('Add Blocked Slot') }}</button>
                            </div>
                        </div>
                    </form>

                    <h5 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">Upcoming Blocked Dates</h5>
                    <div class="relative overflow-y-auto" style="max-height: 400px;">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Date(s)</th>
                                    <th scope="col" class="px-6 py-3">Time</th>
                                    <th scope="col" class="px-6 py-3">Reason</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            @if($leave->start_date->equalTo($leave->end_date))
                                                {{ $leave->start_date->format('d M Y') }}
                                            @else
                                                {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($leave->start_time && $leave->end_time)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                                    {{ \Carbon\Carbon::parse($leave->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($leave->end_time)->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Full Day</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $leave->reason ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClass = match($leave->status) {
                                                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                                };
                                            @endphp
                                            <span class="{{ $statusClass }} text-xs font-medium me-2 px-2.5 py-0.5 rounded">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('doctor.schedule.leave.delete', $leave->id) }}" onsubmit="return confirm('Delete this leave?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2 text-center inline-flex items-center me-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No upcoming leaves or blocked slots.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRow(checkbox) {
        const row = checkbox.closest('tr');
        const inputsContainer = row.querySelector('.flex.items-center');
        const inputs = inputsContainer.querySelectorAll('input[type="time"]');
        const label = checkbox.parentElement.querySelector('span');
        
        if (checkbox.checked) {
            row.classList.remove('opacity-50');
            inputsContainer.classList.remove('opacity-50', 'pointer-events-none');
            inputs.forEach(input => input.removeAttribute('readonly'));
            label.textContent = 'Active';
        } else {
            row.classList.add('opacity-50');
            inputsContainer.classList.add('opacity-50', 'pointer-events-none');
            inputs.forEach(input => input.setAttribute('readonly', 'true'));
            label.textContent = 'Off';
        }
    }
</script>
@endpush
@endsection