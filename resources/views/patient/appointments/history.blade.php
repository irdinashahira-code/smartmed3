@extends('layouts.patient_nextkit')

@section('title', 'Appointment History')

@section('content')
<div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700 rounded-t-lg">
    <div class="w-full mb-1">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Appointment History</h1>
        </div>
        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700">
            <div class="flex items-center mb-4 sm:mb-0">
                <a href="{{ route('patient.dashboard') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Back to Dashboard
                </a>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('patient.appointments.index') }}" class="text-white bg-secondary hover:bg-cyan-600 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">
                    Upcoming Appointments
                </a>
                <a href="{{ route('patient.appointments.create') }}" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Book New
                </a>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col bg-white dark:bg-gray-800 rounded-b-lg shadow-md">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                @if($appointments->isEmpty())
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        You have no past appointments.
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Date</th>
                                <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Time</th>
                                <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Doctor</th>
                                <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Type</th>
                                <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($appointments as $appointment)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                </td>
                                <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </td>
                                <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $appointment->doctor ? $appointment->doctor->name : 'Any Available Doctor' }}
                                </td>
                                <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    {{ ucfirst($appointment->type ?? '-') }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    @if($appointment->status == 'paid' || $appointment->status == 'completed')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-green-900 dark:text-green-300 border border-green-100 dark:border-green-800">Completed</span>
                                        <a href="{{ route('patient.appointments.receipt', $appointment->id) }}" class="text-white bg-info hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Receipt</a>
                                        
                                        @if(!$appointment->feedback)
                                            <a href="{{ route('patient.appointments.history', ['rate_appointment' => $appointment->id]) }}" class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">
                                                <i class="bi bi-star"></i> Rate
                                            </a>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-yellow-900 dark:text-yellow-300 border border-yellow-100 dark:border-yellow-800" title="Rated: {{ $appointment->feedback->rating }} Stars">Rated</span>
                                        @endif

                                    @elseif($appointment->status == 'cancelled')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-red-900 dark:text-red-300 border border-red-100 dark:border-red-800">Cancelled</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-gray-300 border border-gray-100 dark:border-gray-600">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Rating Modal -->
@if(request()->has('rate_appointment'))
    @php
        $ratingAppointment = $appointments->firstWhere('id', request('rate_appointment'));
    @endphp
    
    <!-- Main modal -->
    <div id="ratingModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="ratingModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">
                        Rate Dr. {{ $ratingAppointment && $ratingAppointment->doctor ? $ratingAppointment->doctor->name : 'Doctor' }}
                    </h3>
                    <form action="{{ route('patient.feedback.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ request('rate_appointment') }}">
                        
                        <div class="text-center">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">How was your consultation?</label>
                            <div class="rating-stars flex justify-center space-x-2 text-warning cursor-pointer text-3xl">
                                <i class="bi bi-star text-yellow-300" data-value="1"></i>
                                <i class="bi bi-star text-yellow-300" data-value="2"></i>
                                <i class="bi bi-star text-yellow-300" data-value="3"></i>
                                <i class="bi bi-star text-yellow-300" data-value="4"></i>
                                <i class="bi bi-star text-yellow-300" data-value="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" required>
                        </div>
                        
                        <div>
                            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comments (Optional)</label>
                            <textarea id="comment" name="comment" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flowbite Modal Logic
            const $targetEl = document.getElementById('ratingModal');
            
            // options with default values
            const options = {
                placement: 'center',
                backdrop: 'dynamic',
                backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                closable: true,
                onHide: () => {
                    console.log('modal is hidden');
                },
                onShow: () => {
                    console.log('modal is shown');
                },
                onToggle: () => {
                    console.log('modal has been toggled');
                }
            };

            const modal = new Modal($targetEl, options);
            modal.show();

            // Close button handler
            const closeBtn = $targetEl.querySelector('[data-modal-hide="ratingModal"]');
            if(closeBtn) {
                closeBtn.addEventListener('click', () => {
                    modal.hide();
                });
            }

            // Star Rating Logic
            const stars = document.querySelectorAll('.rating-stars i');
            const ratingInput = document.getElementById('ratingValue');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;
                    updateStars(value);
                });

                star.addEventListener('mouseover', function() {
                    updateStars(this.getAttribute('data-value'));
                });
                
                star.addEventListener('mouseout', function() {
                    updateStars(ratingInput.value || 0);
                });
            });

            function updateStars(value) {
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill');
                    } else {
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                    }
                });
            }
        });
    </script>
@endif
@endsection
