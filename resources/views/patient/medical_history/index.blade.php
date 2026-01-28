@extends('layouts.patient_nextkit')

@section('title', 'My Medical History')

@section('content')
<div class="grid grid-cols-1 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50 dark:bg-gray-700 rounded-t-lg">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('My Medical History') }}</h5>
            <a href="{{ route('patient.dashboard') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Back to Dashboard</a>
        </div>

        <div class="p-6">
            <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                <i class="bi bi-info-circle me-2 text-lg"></i>
                <span class="sr-only">Info</span>
                <div>
                    Please list any chronic conditions, allergies, or past surgeries. This information helps our doctors provide better care.
                </div>
            </div>

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('patient.medical_history.store') }}" class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                @csrf
                <h6 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add New Record</h6>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Condition / Disease</label>
                        <input type="text" name="condition" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="e.g. Diabetes, Asthma" required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <select name="status" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                            <option value="active">Active</option>
                            <option value="chronic">Chronic</option>
                            <option value="recovered">Recovered</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diagnosed Date (Optional)</label>
                        <input type="date" name="diagnosed_date" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary">
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="w-full text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add</button>
                    </div>
                    <div class="md:col-span-12">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description / Notes</label>
                        <textarea name="description" rows="2" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary dark:focus:border-primary" placeholder="Additional details..."></textarea>
                    </div>
                </div>
            </form>

            <h6 class="text-lg font-bold text-gray-900 dark:text-white mt-8 mb-4">Recorded History</h6>
            @if($medicalHistories->isEmpty())
                <p class="text-center text-gray-500 dark:text-gray-400 italic py-3">No medical history recorded yet.</p>
            @else
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Condition</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Diagnosed</th>
                                <th scope="col" class="px-6 py-3">Notes</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicalHistories as $history)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $history->condition }}</td>
                                    <td class="px-6 py-4">
                                        @if($history->status == 'active')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Active</span>
                                        @elseif($history->status == 'chronic')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Chronic</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Recovered</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $history->diagnosed_date ? \Carbon\Carbon::parse($history->diagnosed_date)->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($history->description, 50) }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('patient.medical_history.destroy', $history->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center" title="Delete Record">
                                                <i class="bi bi-trash-fill mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            
            <h6 class="text-lg font-bold text-gray-900 dark:text-white mt-4 mb-4">Doctor Consultation Records</h6>
            @if($consultations->isEmpty())
                <p class="text-center text-gray-500 dark:text-gray-400 italic py-3">No consultation records found.</p>
            @else
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Doctor</th>
                                <th scope="col" class="px-6 py-3">Diagnosis</th>
                                <th scope="col" class="px-6 py-3">Treatment</th>
                                <th scope="col" class="px-6 py-3">Prescription</th>
                                <th scope="col" class="px-6 py-3">Medical Imaging</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultations as $consultation)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $consultation->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $consultation->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $consultation->doctor->name ?? 'Unknown Doctor' }}</div>
                                        <div class="text-xs text-gray-500">{{ $consultation->doctor->specialization ?? 'General' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-primary dark:text-blue-400">{{ $consultation->diagnosis ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $consultation->treatment ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $consultation->prescription ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($consultation->appointment && $consultation->appointment->medicalImages && $consultation->appointment->medicalImages->count() > 0)
                                            <div class="flex flex-col gap-1">
                                                @foreach($consultation->appointment->medicalImages as $image)
                                                    <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center">
                                                        <i class="bi bi-file-earmark-image me-1"></i> {{ $image->file_name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
