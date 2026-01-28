@extends('layouts.admin_nextkit')

@section('title', 'Manage Patients')

@section('content')
<div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
        <div class="w-full md:w-auto">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Patient List</h5>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <form action="{{ route('admin.patients') }}" method="GET" class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search patients..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Search
                </button>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Name</th>
                    <th scope="col" class="px-4 py-3">IC Number</th>
                    <th scope="col" class="px-4 py-3">Contact</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                    <th scope="col" class="px-4 py-3">Registered At</th>
                    <th scope="col" class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-75">
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($patient->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $patient->profile_photo_path) }}" alt="{{ $patient->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-lightprimary text-primary flex items-center justify-center text-sm font-bold">
                                        {{ substr($patient->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $patient->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $patient->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $patient->ic_number }}</td>
                    <td class="px-4 py-3">{{ $patient->phone_number }}</td>
                    <td class="px-4 py-3">
                        <span class="{{ $patient->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} text-xs font-medium px-2.5 py-0.5 rounded">
                            {{ ucfirst($patient->status ?? 'active') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $patient->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.patients.show', $patient->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                View
                            </a>
                            
                            <form action="{{ route('admin.patients.toggle-status', $patient->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-white {{ $patient->status === 'active' ? 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-300 dark:focus:ring-yellow-900' : 'bg-green-600 hover:bg-green-700 focus:ring-green-300 dark:focus:ring-green-800' }} focus:ring-4 font-medium rounded-lg text-xs px-3 py-1.5 focus:outline-none">
                                    {{ $patient->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            @if($patient->status === 'inactive')
                                <form action="{{ route('admin.patients.delete', $patient->id) }}" method="POST" onsubmit="return confirm('Delete this patient permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <button class="text-gray-400 bg-gray-200 cursor-not-allowed font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-gray-700 dark:text-gray-500" disabled title="Deactivate patient first">
                                    Delete
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No patients found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $patients->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection
