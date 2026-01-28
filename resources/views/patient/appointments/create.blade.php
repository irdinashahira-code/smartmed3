@extends('layouts.patient_nextkit')

@section('title', 'Book Appointment')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <div class="md:col-span-8 md:col-start-3">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 rounded-t-lg">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Book New Appointment</h5>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('patient.appointments.preview') }}" class="space-y-6">
                    @csrf

                    <!-- Patient Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right">Full Name</label>
                        <div class="md:col-span-2">
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('name') border-red-500 @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right">IC Number</label>
                        <div class="md:col-span-2">
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('ic_number') border-red-500 @enderror" name="ic_number" value="{{ old('ic_number', Auth::user()->ic_number) }}" required>
                            @error('ic_number')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Optional Weight -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right">Weight (kg) (Optional)</label>
                        <div class="md:col-span-2">
                            <input id="weight" type="number" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('weight') border-red-500 @enderror" name="weight" value="{{ old('weight') }}">
                            @error('weight')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right">Date</label>
                        <div class="md:col-span-2">
                            <input id="date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('date') border-red-500 @enderror" name="date" value="{{ old('date') }}" required min="{{ date('Y-m-d') }}">
                            @error('date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Doctor Selection (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right">Preferred Doctor (Optional)</label>
                        <div class="md:col-span-2">
                            <select id="doctor_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('doctor_id') border-red-500 @enderror" name="doctor_id">
                                <option value="">Any Available Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} ({{ $doctor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Time Slots (Dynamic) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right mt-2">Available Time Slot</label>
                        <div class="md:col-span-2">
                            <div id="time-slots-container" class="flex flex-wrap gap-2">
                                <p class="text-gray-500 text-sm mt-2">Please select a date first.</p>
                            </div>
                            <input type="hidden" name="time" id="selected_time" required>
                            @error('time')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Appointment Type (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right">Appointment Type (Optional)</label>
                        <div class="md:col-span-2">
                            <select id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('type') border-red-500 @enderror" name="type">
                                <option value="">Select Type</option>
                                <option value="vaccination" {{ old('type') == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                                <option value="full body checkup" {{ old('type') == 'full body checkup' ? 'selected' : '' }}>Full Body Checkup</option>
                                <option value="ultrasound checkup" {{ old('type') == 'ultrasound checkup' ? 'selected' : '' }}>Ultrasound Checkup</option>
                                <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>General Consultation</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Consultation Reason (Enhanced) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 md:text-right mt-2">Consultation Reason</label>
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <label for="reason_summary" class="block mb-2 text-xs font-medium text-gray-500 uppercase">Main Symptom / Reason</label>
                                <input type="text" id="reason_summary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="e.g. Fever, Headache" oninput="updateReason()">
                            </div>
                            
                            <div class="mb-4">
                                 <label for="reason_details" class="block mb-2 text-xs font-medium text-gray-500 uppercase">Detailed Description (Optional)</label>
                                 <textarea id="reason_details" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" rows="3" placeholder="e.g. Fever for 3 days with vomiting..." maxlength="300" oninput="updateReason()"></textarea>
                                 <div class="text-right text-xs text-gray-500 mt-1"><span id="char_count">0</span>/300</div>
                            </div>

                            <div class="mb-2">
                                <small class="text-gray-500 me-2">Suggested:</small>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-gray-500 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600" onclick="setReason('Fever')">Fever</span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-gray-500 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600" onclick="setReason('Cough')">Cough</span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-gray-500 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600" onclick="setReason('Flu')">Flu</span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-gray-500 dark:bg-gray-700 dark:text-gray-400 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600" onclick="setReason('General Checkup')">Checkup</span>
                            </div>

                            <input type="hidden" name="reason" id="final_reason" value="{{ old('reason') }}">
                            @error('reason')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <script>
                        function updateReason() {
                            const summary = document.getElementById('reason_summary').value;
                            const details = document.getElementById('reason_details').value;
                            const finalReason = summary + (details ? ' - ' + details : '');
                            document.getElementById('final_reason').value = finalReason;
                            document.getElementById('char_count').innerText = details.length;
                        }

                        function setReason(text) {
                            document.getElementById('reason_summary').value = text;
                            updateReason();
                        }
                    </script>

                    <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const doctorInput = document.getElementById('doctor_id');
    const slotsContainer = document.getElementById('time-slots-container');
    const timeInput = document.getElementById('selected_time');
    let eventSource = null;

    function startSlotStream() {
        const date = dateInput.value;
        const doctorId = doctorInput.value;

        if (!date) return;

        // Close existing connection if any
        if (eventSource) {
            eventSource.close();
            eventSource = null;
        }

        slotsContainer.innerHTML = '<div class="inline-block animate-spin w-4 h-4 border-[3px] border-current border-t-transparent text-primary rounded-full" role="status" aria-label="loading"></div><span class="ms-2 text-gray-500 small">Loading live slots...</span>';
        timeInput.value = '';

        // Initial fetch for immediate display
        fetch(`{{ route('api.slots') }}?date=${date}&doctor_id=${doctorId}`)
            .then(response => response.json())
            .then(data => updateSlots(data))
            .catch(error => {
                console.error('Error fetching slots:', error);
                slotsContainer.innerHTML = '<p class="text-red-500 text-sm mt-2">Error loading slots.</p>';
            });

        // Setup SSE for live updates
        const url = `{{ route('api.slots.stream') }}?date=${date}&doctor_id=${doctorId}`;
        eventSource = new EventSource(url);

        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                updateSlots(data);
            } catch (e) {
                console.error('Error parsing SSE data:', e);
            }
        };

        eventSource.onerror = function(event) {
            // Connection lost or closed. Browser automatically retries.
        };
    }

    function updateSlots(data) {
        const currentSelected = timeInput.value;
        
        if (data.length === 0) {
            const isToday = new Date(dateInput.value).setHours(0,0,0,0) === new Date().setHours(0,0,0,0);
            const message = isToday 
                ? 'No available slots for today.' 
                : 'No slots available for this selection.';
            slotsContainer.innerHTML = `<p class="text-red-500 text-sm mt-2">${message}</p>`;
            timeInput.value = '';
            return;
        }

        // 1. Group slots into periods
        const periods = {
            morning: [],   // 8:00 - 11:59
            afternoon: [], // 12:00 - 15:59
            evening: []    // 16:00 - 21:45
        };

        let stillAvailable = false;

        data.forEach(slot => {
            const hour = parseInt(slot.time.split(':')[0]);
            if (hour >= 8 && hour < 12) {
                periods.morning.push(slot);
            } else if (hour >= 12 && hour < 16) {
                periods.afternoon.push(slot);
            } else if (hour >= 16) {
                periods.evening.push(slot);
            }

            if (slot.time === currentSelected) {
                stillAvailable = true;
            }
        });

        // 2. Determine active period
        let activePeriod = 'morning'; // Default
        if (currentSelected) {
            const hour = parseInt(currentSelected.split(':')[0]);
            if (hour >= 8 && hour < 12) activePeriod = 'morning';
            else if (hour >= 12 && hour < 16) activePeriod = 'afternoon';
            else activePeriod = 'evening';
        } else {
            // Auto-select first period with slots
            if (periods.morning.length > 0) activePeriod = 'morning';
            else if (periods.afternoon.length > 0) activePeriod = 'afternoon';
            else if (periods.evening.length > 0) activePeriod = 'evening';
        }

        // 3. Build UI Structure
        slotsContainer.innerHTML = '';
        const wrapper = document.createElement('div');
        wrapper.className = 'w-full space-y-4';

        // --- Period Tabs ---
        const tabsContainer = document.createElement('div');
        tabsContainer.className = 'flex flex-wrap gap-3';
        
        const periodLabels = {
            morning: 'Morning (8AM - 12PM)',
            afternoon: 'Afternoon (12PM - 4PM)',
            evening: 'Evening (4PM - 9:45PM)'
        };

        ['morning', 'afternoon', 'evening'].forEach(period => {
            const count = periods[period].length;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `flex-1 py-3 px-4 rounded-full text-sm font-semibold transition-all duration-200 border shadow-sm ${
                activePeriod === period 
                ? 'bg-primary text-white border-primary ring-2 ring-primary/20' 
                : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700'
            }`;
            
            // Disable empty periods? Or just show 0?
            // User requested "high-fidelity", keeping them clickable but showing state is better.
            if (count === 0) {
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.disabled = true;
            }
            
            btn.innerHTML = `
                <div class="flex flex-col items-center gap-1">
                    <span>${periodLabels[period]}</span>
                    <span class="text-xs font-normal opacity-80">${count} Slots</span>
                </div>
            `;

            btn.addEventListener('click', () => {
                if (count > 0) {
                    activePeriod = period;
                    renderTabs(); // Update tab styles
                    renderGrid(); // Show slots
                }
            });

            tabsContainer.appendChild(btn);
        });

        // --- Grid Container ---
        const gridContainer = document.createElement('div');
        gridContainer.className = 'grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 animate-fade-in';

        // Helper to update Tab visual state
        function renderTabs() {
            Array.from(tabsContainer.children).forEach((btn, index) => {
                const period = ['morning', 'afternoon', 'evening'][index];
                const isActive = period === activePeriod;
                
                // Remove all possible state classes
                btn.classList.remove('bg-primary', 'text-white', 'border-primary', 'ring-2', 'ring-primary/20');
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200', 'hover:bg-gray-50', 'hover:border-gray-300', 'dark:bg-gray-800', 'dark:text-gray-300', 'dark:border-gray-600', 'dark:hover:bg-gray-700');

                if (isActive) {
                    btn.classList.add('bg-primary', 'text-white', 'border-primary', 'ring-2', 'ring-primary/20');
                } else {
                    btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200', 'hover:bg-gray-50', 'hover:border-gray-300', 'dark:bg-gray-800', 'dark:text-gray-300', 'dark:border-gray-600', 'dark:hover:bg-gray-700');
                }
            });
        }

        // Helper to render Grid
        function renderGrid() {
            gridContainer.innerHTML = '';
            const slots = periods[activePeriod];

            if (slots.length === 0) {
                gridContainer.innerHTML = `<div class="col-span-full py-8 text-center text-gray-500 dark:text-gray-400">
                    <p>No slots available for the ${activePeriod}.</p>
                </div>`;
                return;
            }

            slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                const isSelected = slot.time === timeInput.value;
                
                btn.className = `py-2.5 px-4 rounded-full text-sm font-medium border transition-all duration-200 shadow-sm ${
                    isSelected 
                    ? 'bg-primary text-white border-primary ring-2 ring-primary/20' 
                    : 'bg-white text-gray-700 border-gray-200 hover:border-primary hover:text-primary dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:border-blue-500 dark:hover:text-white'
                }`;
                
                btn.textContent = slot.display;
                btn.dataset.time = slot.time;
                
                btn.addEventListener('click', function() {
                    // Update input
                    timeInput.value = this.dataset.time;
                    
                    // Visual update
                    Array.from(gridContainer.children).forEach(b => {
                        b.className = 'py-2.5 px-4 rounded-full text-sm font-medium border transition-all duration-200 shadow-sm bg-white text-gray-700 border-gray-200 hover:border-primary hover:text-primary dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:border-blue-500 dark:hover:text-white';
                    });
                    this.className = 'py-2.5 px-4 rounded-full text-sm font-medium border transition-all duration-200 shadow-sm bg-primary text-white border-primary ring-2 ring-primary/20';
                });

                gridContainer.appendChild(btn);
            });
        }

        wrapper.appendChild(tabsContainer);
        wrapper.appendChild(gridContainer);
        slotsContainer.appendChild(wrapper);

        // Initial Render
        renderGrid();

        // Handle expired selection
        if (currentSelected && !stillAvailable) {
            timeInput.value = '';
            const msg = document.createElement('p');
            msg.className = 'text-red-500 text-sm mt-2';
            msg.textContent = 'The slot you selected is no longer available.';
            slotsContainer.appendChild(msg);
        }
    }

    dateInput.addEventListener('change', startSlotStream);
    doctorInput.addEventListener('change', startSlotStream);

    // If page loads with data (e.g. after validation error), start stream
    if (dateInput.value) {
        startSlotStream();
    }
});
</script>
@endsection
