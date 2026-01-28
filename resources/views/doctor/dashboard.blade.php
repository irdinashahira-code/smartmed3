@extends('layouts.doctor_nextkit')

@section('title', 'Doctor Dashboard')

@section('content')
    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Queue Management Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-primary text-white p-4 font-bold flex items-center">
            <i class="bi bi-people-fill me-2"></i> {{ __('QUEUE MANAGEMENT') }}
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 text-center">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h5 class="text-gray-500 dark:text-gray-400 font-medium">Current Queue</h5>
                    <h1 class="text-4xl md:text-5xl font-bold text-primary dark:text-blue-500 my-2" id="current-serving">-</h1>
                    <h4 class="text-xl font-medium text-gray-800 dark:text-white mb-2" id="current-serving-name">-</h4>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Serving Now</span>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h5 class="text-gray-500 dark:text-gray-400 font-medium">Active Queue Today</h5>
                    <h1 class="text-4xl md:text-5xl font-bold text-secondary dark:text-teal-400 my-2" id="waiting-count">-</h1>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Patients Waiting</span>
                </div>
            </div>

            <div class="mb-6 flex justify-center gap-2">
                <button onclick="callNext()" class="text-white bg-primary hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 flex items-center">
                    <i class="bi bi-megaphone-fill me-2"></i>Call Next
                </button>
            </div>

            <h5 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Live Queue List</h5>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="queue-table">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Q.No</th>
                            <th scope="col" class="px-6 py-3">Patient</th>
                            <th scope="col" class="px-6 py-3">Reason</th>
                            <th scope="col" class="px-6 py-3">Time</th>
                            <th scope="col" class="px-6 py-3">Wait Time</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="queue-list-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Clinic Overview Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('doctor.appointments.today') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 border-l-4 border-l-primary transition-transform hover:-translate-y-1">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $todayAppointmentsCount }}</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400 uppercase text-xs tracking-wider flex items-center">
                <i class="bi bi-calendar-day me-2"></i> Today's Appointments
            </p>
        </a>
    </div>

    <!-- Cancellation Requests -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-red-500 text-white p-4 font-bold flex items-center">
            <i class="bi bi-x-circle me-2"></i> {{ __('Cancellation Requests') }}
        </div>
        <div class="p-6">
            @if($cancellationRequests->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 italic">No pending cancellation requests.</p>
            @else
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Patient</th>
                                <th scope="col" class="px-6 py-3">IC Number</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Time</th>
                                <th scope="col" class="px-6 py-3">Reason</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cancellationRequests as $appointment)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $appointment->patient->name }}</td>
                                <td class="px-6 py-4">{{ $appointment->patient->ic_number }}</td>
                                <td class="px-6 py-4">{{ $appointment->appointment_date }}</td>
                                <td class="px-6 py-4">{{ $appointment->appointment_time }}</td>
                                <td class="px-6 py-4">{{ $appointment->reason ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('doctor.appointments.approve-cancel', $appointment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-2 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">Approve</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Reschedule Requests -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-yellow-400 text-gray-900 p-4 font-bold flex items-center">
            <i class="bi bi-clock-history me-2"></i> {{ __('Reschedule Requests') }}
        </div>
        <div class="p-6">
            @if($rescheduleRequests->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 italic">No pending reschedule requests.</p>
            @else
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Patient</th>
                                <th scope="col" class="px-6 py-3">IC Number</th>
                                <th scope="col" class="px-6 py-3">Current Date/Time</th>
                                <th scope="col" class="px-6 py-3">Reason</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rescheduleRequests as $appointment)
                            @php
                                $newData = $appointment->reschedule_data;
                            @endphp
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $appointment->patient->name }}</td>
                                <td class="px-6 py-4">{{ $appointment->patient->ic_number }}</td>
                                <td class="px-6 py-4">{{ $appointment->appointment_date }} {{ $appointment->appointment_time }}</td>
                                <td class="px-6 py-4">
                                    <div>Requested: <span class="font-bold">{{ $newData['date'] }}</span> at <span class="font-bold">{{ $newData['time'] }}</span></div>
                                    <small class="text-gray-500">{{ $newData['reason'] ?? $appointment->reason ?? '-' }}</small>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('doctor.appointments.approve-reschedule', $appointment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-900">Approve</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-primary text-white p-4 font-bold flex justify-between items-center">
            <div><i class="bi bi-calendar-event me-2"></i> {{ __('Upcoming Appointments') }}</div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="filterReasons" class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ms-3 text-sm font-medium text-white">Show only with reasons</span>
            </label>
        </div>
        <div class="p-6">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="appointments-table">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Patient</th>
                            <th scope="col" class="px-6 py-3">IC Number</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Time</th>
                            <th scope="col" class="px-6 py-3">Reason</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody id="appointments-table-body">
                        @if($upcomingAppointments->isEmpty())
                            <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 italic">No upcoming appointments.</td></tr>
                        @else
                            @foreach($upcomingAppointments as $appointment)
                            <tr class="appointment-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-has-reason="{{ !empty($appointment->reason) ? 'true' : 'false' }}">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $appointment->patient->name }}</td>
                                <td class="px-6 py-4">{{ $appointment->patient->ic_number }}</td>
                                <td class="px-6 py-4">{{ $appointment->appointment_date }}</td>
                                <td class="px-6 py-4">{{ $appointment->appointment_time }}</td>
                                <td class="px-6 py-4">
                                    @if($appointment->reason)
                                        <span class="block truncate max-w-[150px]" title="{{ $appointment->reason }}">
                                            {{ $appointment->reason }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($appointment->cancellation_status == 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Cancellation Pending</span>
                                    @elseif($appointment->reschedule_status == 'pending')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Reschedule Pending</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Confirmed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button class="text-primary hover:text-blue-700 border border-primary hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800" onclick="openDetailsModal({{ $appointment->id }})">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Appointment Details Modal (Flowbite) -->
    <div id="appointmentDetailsModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 bg-primary text-white">
                    <h3 class="text-xl font-semibold">
                        Consultation Details
                    </h3>
                    <button type="button" class="text-white bg-transparent hover:bg-blue-700 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" onclick="closeDetailsModal()">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-0">
                    <div id="modal-loading" class="text-center py-10">
                        <div role="status">
                            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Loading patient data...</p>
                    </div>
                    <div id="modal-content" style="display: none;">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                            <!-- Left Panel: Patient History & Previous Notes -->
                            <div class="lg:col-span-1 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-600 overflow-y-auto max-h-[70vh]">
                                <div class="p-4">
                                    <h6 class="font-bold text-gray-500 dark:text-gray-400 mb-3 text-xs uppercase tracking-wider">Patient Info</h6>
                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm mb-4">
                                        <div class="p-4 text-center">
                                            <div id="patient-photo-container" class="mb-2 flex justify-center"></div>
                                            <h5 class="text-lg font-bold text-gray-900 dark:text-white" id="patient-name">-</h5>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">IC: <span id="patient-ic">-</span></p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Age: <span id="patient-age">-</span></p>
                                        </div>
                                    </div>

                                    <h6 class="font-bold text-gray-500 dark:text-gray-400 mb-3 text-xs uppercase tracking-wider">Medical History</h6>
                                    <div id="medical-history-list" class="mb-4 space-y-2">
                                        <!-- Populated via JS -->
                                    </div>

                                    <h6 class="font-bold text-gray-500 dark:text-gray-400 mb-3 text-xs uppercase tracking-wider">Previous Consultations (With You)</h6>
                                    <div id="previous-notes-list" class="space-y-2">
                                        <!-- Populated via JS -->
                                    </div>
                                </div>
                            </div>

                            <!-- Right Panel: Current Consultation -->
                            <div class="lg:col-span-2 overflow-y-auto max-h-[70vh]">
                                <div class="p-6">
                                    <div class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800 flex items-center">
                                        <i class="bi bi-info-circle-fill me-2 text-xl"></i>
                                        <div>
                                            <div class="text-xs font-bold uppercase">Consultation Reason</div>
                                            <div id="current-reason" class="text-lg font-medium">-</div>
                                        </div>
                                    </div>

                                    <form id="consultation-form">
                                        <h6 class="font-bold text-primary dark:text-blue-500 mb-3 uppercase text-xs tracking-wider">Doctor's Notes (Private)</h6>
                                        <div class="mb-4">
                                            <textarea class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="private_notes" rows="3" placeholder="Private notes visible only to you..."></textarea>
                                        </div>

                                        <hr class="my-4 border-gray-200 dark:border-gray-700">

                                        <h6 class="font-bold text-green-600 dark:text-green-400 mb-3 uppercase text-xs tracking-wider">Consultation Record</h6>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Diagnosis</label>
                                                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="diagnosis" placeholder="Primary Diagnosis">
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Treatment / Advice</label>
                                                <textarea class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="treatment" rows="2" placeholder="Treatment plan..."></textarea>
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Medical Imaging</label>
                                                <div class="flex">
                                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-l-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="medical_image_upload" type="file" accept="image/*">
                                                    <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-500" type="button" onclick="uploadImage()">
                                                        Upload
                                                    </button>
                                                </div>
                                                <div id="image-preview-area" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                                    <!-- Uploaded images will appear here -->
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Prescription</label>
                                                <textarea class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="prescription" rows="2" placeholder="Medications..."></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 bg-gray-50 dark:bg-gray-700">
                    <input type="hidden" id="current-appointment-id">
                    <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 me-2" onclick="closeDetailsModal()">Close</button>
                    <button type="button" id="btn-save-notes" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center">
                        <i class="bi bi-save me-2"></i> Save Consultation Record
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let detailsModal;
    let appointmentEventSource;
    let queueEventSource;

    document.addEventListener('DOMContentLoaded', function() {
        // Save Button Event Listener
        const saveBtn = document.getElementById('btn-save-notes');
        if (saveBtn) {
            saveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                saveNotes();
            });
        }

        // Queue Management
        fetchDoctorQueueState();
        initQueueStream();

        // Initialize Flowbite Modal
        const modalEl = document.getElementById('appointmentDetailsModal');
        if (modalEl) {
             const options = {
                placement: 'center',
                backdrop: 'dynamic',
                backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                closable: true,
            };
            // Check if Modal is defined (Flowbite)
            if (typeof Modal !== 'undefined') {
                detailsModal = new Modal(modalEl, options);
            } else {
                console.error('Flowbite Modal not defined');
            }
        }

        // Appointment Stream
        initAppointmentStream();

        // Filter Logic
        const filterCheckbox = document.getElementById('filterReasons');
        if (filterCheckbox) {
            filterCheckbox.addEventListener('change', function() {
                filterAppointments(this.checked);
            });
        }
    });

    // Helper to close modal since we use programmatic control
    window.closeDetailsModal = function() {
        if(detailsModal) detailsModal.hide();
    }

    // --- Image Upload Logic ---
    window.performImageUpload = function() {
        return new Promise((resolve, reject) => {
            const fileInput = document.getElementById('medical_image_upload');
            const file = fileInput.files[0];
            const appointmentId = document.getElementById('current-appointment-id').value;

            if (!file) {
                resolve({ uploaded: false });
                return;
            }

            if (!appointmentId) {
                reject("No active appointment selected.");
                return;
            }

            const formData = new FormData();
            formData.append('image', file);
            formData.append('appointment_id', appointmentId);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("doctor.medical-images.upload") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fileInput.value = '';
                    addImageToPreview(data.image);
                    resolve({ uploaded: true, data: data });
                } else {
                    reject(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                reject(error);
            });
        });
    }

    window.uploadImage = function() {
        const fileInput = document.getElementById('medical_image_upload');
        if (!fileInput.files[0]) {
            alert("Please select a file first.");
            return;
        }

        const btn = event.target;
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerText = "Uploading...";

        performImageUpload()
            .then(() => {
                alert("Image uploaded successfully!");
            })
            .catch(err => {
                console.error('Error:', err);
                alert("Upload failed: " + err);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerText = originalText;
            });
    }

    window.addImageToPreview = function(image) {
        const container = document.getElementById('image-preview-area');
        const col = document.createElement('div');
        col.className = 'relative';
        
        const imageUrl = image.url;
        
        col.innerHTML = `
            <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 h-full">
                <a href="${imageUrl}" target="_blank">
                    <img class="rounded-t-lg w-full h-24 object-cover" src="${imageUrl}" alt="Medical Image" />
                </a>
                <div class="p-2 text-center bg-gray-50 dark:bg-gray-700 rounded-b-lg">
                    <p class="mb-1 text-xs font-normal text-gray-700 dark:text-gray-400 truncate">${image.file_name}</p>
                </div>
            </div>
        `;
        container.prepend(col);
    }

    // --- Appointment Stream & Filter Logic ---
    function initAppointmentStream() {
        if (appointmentEventSource) {
            appointmentEventSource.close();
        }

        appointmentEventSource = new EventSource('{{ route("doctor.appointments.stream") }}');

        appointmentEventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            updateAppointmentsTable(data);
        };

        appointmentEventSource.onerror = function(err) {
            console.error("EventSource failed:", err);
        };
    }

    function updateAppointmentsTable(appointments) {
        const tbody = document.getElementById('appointments-table-body');
        const filterChecked = document.getElementById('filterReasons').checked;

        if (appointments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 italic">No upcoming appointments.</td></tr>';
            return;
        }

        let html = '';
        appointments.forEach(apt => {
            const hasReason = apt.reason && apt.reason.trim() !== '';
            
            if (filterChecked && !hasReason) {
                return;
            }

            const newBadge = apt.is_new ? '<span class="bg-red-100 text-red-800 text-xs font-medium ms-1 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300 blink-me">NEW</span>' : '';
            
            // Map status type to Tailwind classes
            let badgeClass = '';
            if (apt.type.class === 'bg-success') badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
            else if (apt.type.class === 'bg-warning') badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
            else if (apt.type.class === 'bg-info') badgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
            else badgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';

            html += `
                <tr class="appointment-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-has-reason="${hasReason}">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${apt.patient_name} ${newBadge}</td>
                    <td class="px-6 py-4">${apt.ic_number}</td>
                    <td class="px-6 py-4">${apt.date}</td>
                    <td class="px-6 py-4">${apt.time}</td>
                    <td class="px-6 py-4">
                        ${hasReason 
                            ? `<span class="block truncate max-w-[150px]" title="${apt.reason}">${apt.reason}</span>` 
                            : '<span class="text-gray-400 text-xs">-</span>'}
                    </td>
                    <td class="px-6 py-4"><span class="${badgeClass} text-xs font-medium px-2.5 py-0.5 rounded">${apt.type.label}</span></td>
                    <td class="px-6 py-4">
                        <button class="text-primary hover:text-blue-700 border border-primary hover:bg-blue-50 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800" onclick="openDetailsModal(${apt.id})">
                            View Details
                        </button>
                    </td>
                </tr>
            `;
        });

        if (html === '') {
             tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 italic">No appointments match filter.</td></tr>';
        } else {
            tbody.innerHTML = html;
        }
    }

    function filterAppointments(showOnlyReasons) {
        const rows = document.querySelectorAll('.appointment-row');
        
        rows.forEach(row => {
            const hasReason = row.getAttribute('data-has-reason') === 'true';
            if (showOnlyReasons && !hasReason) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
            }
        });
    }

    // --- Queue Management Logic ---

    function initQueueStream() {
        if (queueEventSource) {
            queueEventSource.close();
        }

        queueEventSource = new EventSource('{{ route("doctor.queue.stream") }}');

        queueEventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            renderQueueTable(data);
        };

        queueEventSource.onerror = function(err) {
            console.error("Queue EventSource failed:", err);
        };
    }

    function renderQueueTable(data) {
        const currentServing = document.getElementById('current-serving');
        const currentServingName = document.getElementById('current-serving-name');
        const waitingCount = document.getElementById('waiting-count');
        const queueListBody = document.getElementById('queue-list-body');
        
        if(currentServing) currentServing.innerText = data.current_serving;
        if(currentServingName) currentServingName.innerText = data.current_serving_name;
        if(waitingCount) waitingCount.innerText = data.waiting_count;
        
        if(queueListBody) {
            queueListBody.innerHTML = '';
            
            const list = data.queue_list;
            if (!list || list.length === 0) {
                queueListBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No active queue.</td></tr>';
                return;
            }

            const queueArray = Array.isArray(list) ? list : Object.values(list);

            queueArray.forEach(item => {
                let actionButtons = '';
                const status = item.status || 'Waiting'; 
                
                if (status === 'Called' || status === 'Consulting') {
                    actionButtons = `
                        <button onclick="openDetailsModal(${item.id})" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-xs px-3 py-2 me-1 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700" title="Consultation Details"><i class="bi bi-file-medical"></i></button>
                        <button onclick="updateQueue(${item.id}, 'consulting')" class="text-white bg-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 me-1 dark:focus:ring-blue-900">In Consultation</button>
                        <button onclick="updateQueue(${item.id}, 'complete')" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-xs px-3 py-2 me-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Complete</button>
                        <button onclick="updateQueue(${item.id}, 'skip')" class="text-white bg-yellow-400 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-2 dark:focus:ring-yellow-900">Skip</button>
                    `;
                } else if (status === 'Waiting') {
                    actionButtons = `
                        <button onclick="openDetailsModal(${item.id})" class="py-2 px-3 me-1 text-xs font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" title="View Details"><i class="bi bi-eye"></i></button>
                        <button onclick="updateQueue(${item.id}, 'call')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Call</button>
                    `;
                } else {
                    actionButtons = '<span class="text-gray-400">Completed/Skipped</span>';
                }

                const row = `
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">${item.queue_number}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${item.patient_name}</td>
                        <td class="px-6 py-4">
                            ${item.reason 
                                ? `<span class="block truncate max-w-[150px]" title="${item.reason}">${item.reason}</span>` 
                                : '<span class="text-gray-400 text-xs">-</span>'}
                        </td>
                        <td class="px-6 py-4">${item.time}</td>
                        <td class="px-6 py-4">${item.wait_time}</td>
                        <td class="px-6 py-4"><span class="${getStatusColor(status)} text-xs font-medium px-2.5 py-0.5 rounded">${status}</span></td>
                        <td class="px-6 py-4">${actionButtons}</td>
                    </tr>
                `;
                queueListBody.innerHTML += row;
            });
        }
    }
    
    window.fetchDoctorQueueState = function() {
        fetch('{{ route("doctor.queue.state") }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                renderQueueTable(data);
            })
            .catch(error => console.error('Error fetching queue state:', error));
    }

    window.callNext = function() {
        fetch('{{ route("doctor.queue.call-next") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                fetchDoctorQueueState();
            } else {
                alert(data.message);
            }
        });
    }

    window.updateQueue = function(id, action) {
        fetch(`/doctor/queue/${id}/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) fetchDoctorQueueState();
        });
    }

    function getStatusColor(status) {
        switch(status) {
            case 'Waiting': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
            case 'Called': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 blink-me';
            case 'Consulting': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
            case 'Completed': return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            case 'Skipped': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
            default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        }
    }

    // --- Modal Logic ---
    window.openDetailsModal = function(appointmentId) {
        document.getElementById('current-appointment-id').value = appointmentId;
        document.getElementById('modal-loading').style.display = 'block';
        document.getElementById('modal-content').style.display = 'none';
        
        if(detailsModal) detailsModal.show();

        // Fetch details
        fetch(`/doctor/appointments/${appointmentId}/details`)
            .then(response => {
                if (!response.ok) throw new Error('Unauthorized or not found');
                return response.json();
            })
            .then(data => {
                populateModal(data);
                document.getElementById('modal-loading').style.display = 'none';
                document.getElementById('modal-content').style.display = 'block';
            })
            .catch(err => {
                alert('Error loading details: ' + err.message);
                if(detailsModal) detailsModal.hide();
            });
    }

    window.populateModal = function(data) {
        // Patient Info
        document.getElementById('patient-name').innerText = data.patient.name;
        document.getElementById('patient-ic').innerText = data.patient.ic_number || '-';
        document.getElementById('patient-age').innerText = data.patient.date_of_birth ? calculateAge(data.patient.date_of_birth) : '-';

        // Patient Photo
        const photoContainer = document.getElementById('patient-photo-container');
        if (data.patient.profile_photo_url) {
            photoContainer.innerHTML = `<img src="${data.patient.profile_photo_url}" class="rounded-full w-24 h-24 object-cover border-4 border-white shadow-sm">`;
        } else {
            const initial = data.patient.name.charAt(0).toUpperCase();
            photoContainer.innerHTML = `
                <div class="w-24 h-24 rounded-full bg-primary text-white flex items-center justify-center text-4xl font-bold border-4 border-white shadow-sm">
                    ${initial}
                </div>
            `;
        }

        // Reason
        document.getElementById('current-reason').innerText = data.appointment.reason || 'No specific reason provided.';

        // Medical History
        const historyList = document.getElementById('medical-history-list');
        historyList.innerHTML = '';
        if (data.medical_history.length === 0) {
            historyList.innerHTML = '<p class="text-gray-500 text-xs italic">No registered medical history.</p>';
        } else {
            data.medical_history.forEach(hist => {
                const date = hist.diagnosed_date ? new Date(hist.diagnosed_date).toLocaleDateString() : 'Unknown Date';
                let statusClass = 'bg-gray-100 text-gray-800';
                if (hist.status === 'active') statusClass = 'bg-red-100 text-red-800';
                else if (hist.status === 'recovered') statusClass = 'bg-green-100 text-green-800';
                
                historyList.innerHTML += `
                    <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between items-start mb-1">
                            <div class="font-bold text-gray-900 dark:text-white text-sm">${hist.condition}</div>
                            <span class="${statusClass} text-xs font-medium px-2 py-0.5 rounded uppercase">${hist.status}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Since: ${date}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 italic border-t border-gray-100 dark:border-gray-600 pt-1 mt-1">${hist.description || 'No notes.'}</div>
                    </div>`;
            });
        }

        // Previous Notes
        const notesList = document.getElementById('previous-notes-list');
        notesList.innerHTML = '';
        if (data.previous_notes.length === 0) {
            notesList.innerHTML = '<p class="text-gray-500 text-xs italic">No previous records with you.</p>';
        } else {
            data.previous_notes.forEach(note => {
                const date = new Date(note.created_at).toLocaleDateString();
                notesList.innerHTML += `
                    <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>${date}</span>
                        </div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white mb-1">${note.diagnosis || 'No Diagnosis'}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${note.treatment || ''}</div>
                    </div>`;
            });
        }

        // Current Notes
        const note = data.current_note || {};
        document.getElementById('diagnosis').value = note.diagnosis || '';
        document.getElementById('treatment').value = note.treatment || '';
        document.getElementById('prescription').value = note.prescription || '';
        document.getElementById('private_notes').value = note.private_notes || '';

        // Populate Images
        const imageContainer = document.getElementById('image-preview-area');
        imageContainer.innerHTML = ''; // Clear previous images
        if (data.medical_images && data.medical_images.length > 0) {
            data.medical_images.forEach(image => {
                addImageToPreview(image);
            });
        }
    }

    window.saveNotes = async function() {
        const appointmentIdInput = document.getElementById('current-appointment-id');
        if (!appointmentIdInput || !appointmentIdInput.value) {
            alert('Error: No appointment selected or ID missing.');
            return;
        }
        
        const btn = document.getElementById('btn-save-notes');
        const originalText = btn.innerHTML;

        // --- 1. Auto-upload Image if selected ---
        const fileInput = document.getElementById('medical_image_upload');
        if (fileInput && fileInput.files.length > 0) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading Image...';
            
            try {
                await performImageUpload();
            } catch (err) {
                // If upload fails, stop and warn user
                alert("Image upload failed: " + err + ". Notes were NOT saved.");
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }
        }

        // --- 2. Save Notes ---
        const appointmentId = appointmentIdInput.value;
        const data = {
            diagnosis: document.getElementById('diagnosis').value,
            treatment: document.getElementById('treatment').value,
            prescription: document.getElementById('prescription').value,
            private_notes: document.getElementById('private_notes').value,
            _token: '{{ csrf_token() }}'
        };

        btn.disabled = true;
        btn.innerHTML = '<svg aria-hidden="true" class="w-4 h-4 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg> Saving...';

        fetch(`/doctor/appointments/${appointmentId}/notes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                btn.innerHTML = '<i class="bi bi-check me-2"></i> Saved!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    detailsModal.hide();
                }, 1000);
            } else {
                alert('Error saving notes.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error saving notes.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function calculateAge(dateString) {
        const today = new Date();
        const birthDate = new Date(dateString);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
</script>
<style>
    .blink-me {
        animation: blinker 1s linear infinite;
    }
    @keyframes blinker {
        50% { opacity: 0; }
    }
</style>
@endpush
