@extends('layouts.admin_nextkit')

@section('title', 'Manage Doctors')

@section('content')
<div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
        <div class="w-full md:w-auto">
             <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Doctor List</h5>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
            <form action="{{ route('admin.doctors') }}" method="GET" class="flex items-center w-full md:w-auto">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search doctors..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="ml-2 text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Search</button>
            </form>
            <a href="{{ route('admin.doctors.create') }}" class="flex items-center justify-center text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                </svg>
                Add Doctor
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-4 py-3">Name</th>
                    <th scope="col" class="px-4 py-3">Email</th>
                    <th scope="col" class="px-4 py-3">Specialization</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                    <th scope="col" class="px-4 py-3">Registered At</th>
                    <th scope="col" class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex items-center">
                            @if($doctor->profile_photo_path)
                                <img class="w-10 h-10 rounded-full object-cover mr-3" src="{{ asset('storage/' . $doctor->profile_photo_path) }}" alt="{{ $doctor->name }}">
                            @else
                                <div class="w-10 h-10 rounded-full bg-lightprimary text-primary flex items-center justify-center font-bold text-lg mr-3">
                                    {{ substr($doctor->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="font-semibold">{{ $doctor->name }}</div>
                                <div class="text-xs text-gray-500">{{ $doctor->phone_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $doctor->email }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $doctor->specialization ?? 'N/A' }}</span>
                    </td>
                    <td class="px-4 py-3">
                         @if($doctor->status == 'active')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Active</span>
                        @elseif($doctor->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                        @elseif($doctor->status == 'rejected')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Rejected</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $doctor->status }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $doctor->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" title="Edit Profile">
                                <i class="bi bi-pencil text-lg"></i>
                            </a>
                            <a href="{{ route('admin.doctors.schedule', $doctor->id) }}" class="font-medium text-cyan-600 dark:text-cyan-500 hover:underline" title="Manage Schedule">
                                <i class="bi bi-calendar-week text-lg"></i>
                            </a>
                            
                            @if($doctor->status == 'pending')
                                <form action="{{ route('admin.doctors.approve', $doctor->id) }}" method="POST" onsubmit="return confirm('Approve this doctor?');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="font-medium text-green-600 dark:text-green-500 hover:underline border-0 bg-transparent p-0 cursor-pointer" title="Approve"><i class="bi bi-check-lg text-lg"></i></button>
                                </form>
                                <form action="{{ route('admin.doctors.reject', $doctor->id) }}" method="POST" onsubmit="return confirm('Reject this doctor?');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline border-0 bg-transparent p-0 cursor-pointer" title="Reject"><i class="bi bi-x-lg text-lg"></i></button>
                                </form>
                            @elseif($doctor->status == 'active')
                                <form action="{{ route('admin.doctors.reject', $doctor->id) }}" method="POST" onsubmit="return confirm('Deactivate this doctor account?');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline border-0 bg-transparent p-0 cursor-pointer" title="Deactivate"><i class="bi bi-ban text-lg"></i></button>
                                </form>
                            @else
                                <form action="{{ route('admin.doctors.approve', $doctor->id) }}" method="POST" onsubmit="return confirm('Re-activate this doctor account?');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="font-medium text-green-600 dark:text-green-500 hover:underline border-0 bg-transparent p-0 cursor-pointer" title="Activate"><i class="bi bi-check-circle text-lg"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">No doctors found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $doctors->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection