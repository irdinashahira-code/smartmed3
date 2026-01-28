@extends('layouts.patient_nextkit')

@section('title', 'Reschedule Appointment')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Reschedule Appointment</h1>
        </div>
    </div>
</div>

<div class="flex flex-col bg-white dark:bg-gray-800 rounded-b-lg shadow-md p-6">
    <form method="POST" action="{{ route('patient.appointments.reschedule.submit', $appointment->id) }}" class="space-y-6">
        @csrf

        <!-- Patient Details (Read Only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                <input type="text" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ Auth::user()->name }}" readonly>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">IC Number</label>
                <input type="text" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ Auth::user()->ic_number }}" readonly>
            </div>
        </div>

        <!-- Optional Weight -->
        <div>
            <label for="weight" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Weight (kg) (Optional)</label>
            <input type="number" step="0.01" name="weight" id="weight" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('weight') border-red-500 @enderror" value="{{ old('weight', $appointment->weight) }}">
            @error('weight')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date & Doctor Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date</label>
                <input type="date" name="date" id="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('date') border-red-500 @enderror" value="{{ old('date', $appointment->appointment_date) }}" required min="{{ date('Y-m-d') }}">
                @error('date')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="doctor_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Preferred Doctor (Optional)</label>
                <select id="doctor_id" name="doctor_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('doctor_id') border-red-500 @enderror">
                    <option value="">Any Available Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
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
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Time Slot</label>
            <div id="time-slots-container" class="flex flex-wrap gap-2 min-h-[50px] p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Please select a date first.</p>
            </div>
            <input type="hidden" name="time" id="selected_time" required value="{{ old('time') }}">
            @error('time')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Appointment Type (Optional) -->
        <div>
            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Appointment Type (Optional)</label>
            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('type') border-red-500 @enderror">
                <option value="">Select Type</option>
                <option value="vaccination" {{ old('type', $appointment->type) == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                <option value="full body checkup" {{ old('type', $appointment->type) == 'full body checkup' ? 'selected' : '' }}>Full Body Checkup</option>
                <option value="ultrasound checkup" {{ old('type', $appointment->type) == 'ultrasound checkup' ? 'selected' : '' }}>Ultrasound Checkup</option>
                <option value="consultation" {{ old('type', $appointment->type) == 'consultation' ? 'selected' : '' }}>General Consultation</option>
            </select>
            @error('type')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reason (Optional) -->
        <div>
            <label for="reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Consultation Reason (Optional)</label>
            <textarea id="reason" name="reason" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary @error('reason') border-red-500 @enderror" placeholder="e.g. fever, diarrhea, and cough">{{ old('reason', $appointment->reason) }}</textarea>
            @error('reason')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end space-x-2 border-t border-gray-200 dark:border-gray-700 pt-6">
            <a href="{{ route('patient.appointments.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Cancel</a>
            <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Submit Reschedule Request
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const doctorInput = document.getElementById('doctor_id');
    const slotsContainer = document.getElementById('time-slots-container');
    const timeInput = document.getElementById('selected_time');

    function fetchSlots() {
        const date = dateInput.value;
        const doctorId = doctorInput.value;

        if (!date) return;

        slotsContainer.innerHTML = '<div class="inline-block animate-spin w-4 h-4 border-[3px] border-current border-t-transparent text-primary rounded-full" role="status" aria-label="loading"></div><span class="ms-2 text-gray-500 dark:text-gray-400 text-sm">Loading slots...</span>';
        timeInput.value = '';

        fetch(`{{ route('api.slots') }}?date=${date}&doctor_id=${doctorId}`)
            .then(response => response.json())
            .then(data => {
                slotsContainer.innerHTML = '';
                if (data.length === 0) {
                    const isToday = new Date(date).setHours(0,0,0,0) === new Date().setHours(0,0,0,0);
                    const message = isToday 
                        ? 'No available slots for today.' 
                        : 'No slots available for this selection.';
                    slotsContainer.innerHTML = `<p class="text-red-500 text-sm">${message}</p>`;
                } else {
                    data.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        // Base classes for Tailwind button
                        btn.className = 'text-primary hover:text-white border border-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800 slot-btn transition-colors duration-200';
                        btn.textContent = slot.display;
                        btn.dataset.time = slot.time;
                        
                        btn.addEventListener('click', function() {
                            // Reset all buttons to outline style
                            document.querySelectorAll('.slot-btn').forEach(b => {
                                b.className = 'text-primary hover:text-white border border-primary hover:bg-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800 slot-btn transition-colors duration-200';
                            });
                            // Set active style for clicked button
                            this.className = 'text-white bg-primary border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:border-blue-600 dark:focus:ring-blue-800 slot-btn transition-colors duration-200';
                            
                            timeInput.value = this.dataset.time;
                        });

                        slotsContainer.appendChild(btn);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                slotsContainer.innerHTML = '<p class="text-red-500 text-sm">Error loading slots.</p>';
            });
    }

    dateInput.addEventListener('change', fetchSlots);
    doctorInput.addEventListener('change', fetchSlots);

    // Initial fetch if date is present (e.g. on load with existing data)
    if (dateInput.value) {
        fetchSlots();
    }
});
</script>
@endsection
