@extends('layouts.admin_nextkit')

@section('title', 'Medical History: ' . $patient->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 inline-flex items-center">
        <i class="bi bi-arrow-left me-2"></i> Back to Profile
    </a>
</div>

<div class="grid grid-cols-1 gap-6">
    <!-- Doctor Consultations -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-primary dark:text-blue-500 flex items-center">
                <i class="bi bi-stethoscope me-2"></i>Doctor Consultation Records
            </h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Date</th>
                        <th scope="col" class="px-4 py-3">Doctor</th>
                        <th scope="col" class="px-4 py-3">Diagnosis</th>
                        <th scope="col" class="px-4 py-3">Treatment</th>
                        <th scope="col" class="px-4 py-3">Prescription</th>
                        <th scope="col" class="px-4 py-3">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $consultation)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75">
                        <td class="px-4 py-3">{{ $consultation->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-gray-900 dark:text-white">{{ $consultation->doctor->name }}</span>
                            <br>
                            <small class="text-gray-500 dark:text-gray-400">{{ $consultation->doctor->specialization }}</small>
                        </td>
                        <td class="px-4 py-3">{{ $consultation->diagnosis }}</td>
                        <td class="px-4 py-3">{{ $consultation->treatment }}</td>
                        <td class="px-4 py-3">{{ $consultation->prescription ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $consultation->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No consultation records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Patient Self-Reported History -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-semibold text-secondary dark:text-cyan-500 flex items-center">
                <i class="bi bi-person-lines-fill me-2"></i>Patient Self-Reported History
            </h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Condition</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3">Date Recorded</th>
                        <th scope="col" class="px-4 py-3">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicalHistories as $history)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75">
                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">{{ $history->condition }}</td>
                        <td class="px-4 py-3">
                            @if($history->status === 'Active')
                                <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                            @elseif($history->status === 'Recovered')
                                <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium px-2.5 py-0.5 rounded">Recovered</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded">{{ $history->status }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($history->diagnosed_date)->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $history->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No self-reported history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
