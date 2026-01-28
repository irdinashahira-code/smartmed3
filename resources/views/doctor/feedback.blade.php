@extends('layouts.doctor_nextkit')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1">
        <div class="col-span-1">
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-6 overflow-hidden">
                <div class="bg-primary text-white p-4 font-bold flex items-center">
                    <i class="bi bi-star me-2"></i> {{ __('My Ratings & Reviews') }}
                </div>
                <div class="p-6">
                    @if($feedbacks->isEmpty())
                        <div class="text-center py-10">
                            <i class="bi bi-chat-square-text text-gray-400 dark:text-gray-500 text-6xl"></i>
                            <p class="mt-3 text-gray-500 dark:text-gray-400">No feedback received yet.</p>
                        </div>
                    @else
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3" style="width: 15%">Date</th>
                                        <th scope="col" class="px-6 py-3" style="width: 20%">Patient</th>
                                        <th scope="col" class="px-6 py-3" style="width: 15%">Rating</th>
                                        <th scope="col" class="px-6 py-3" style="width: 50%">Comment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $feedback->created_at->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $feedback->created_at->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($feedback->appointment && $feedback->appointment->patient)
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold me-2">
                                                        {{ substr($feedback->appointment->patient->name, 0, 1) }}
                                                    </div>
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $feedback->appointment->patient->name }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400 italic">Unknown Patient</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex text-yellow-400 mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $feedback->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                    @else
                                                        <i class="bi bi-star text-gray-300 dark:text-gray-600"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                {{ $feedback->rating }}.0 / 5.0
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($feedback->comment)
                                                <p class="mb-0 text-gray-500 dark:text-gray-400 break-words">"{{ $feedback->comment }}"</p>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 italic">No comment provided.</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $feedbacks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
