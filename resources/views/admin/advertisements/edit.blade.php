@extends('layouts.admin_nextkit')

@section('title', 'Edit Campaign')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
        <a href="{{ route('admin.advertisements.index') }}" class="hover:text-primary">Advertisements</a>
        <i class="bi bi-chevron-right text-xs"></i>
        <span>Edit Campaign</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Campaign: {{ $advertisement->title }}</h1>
</div>

<form action="{{ route('admin.advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Step 1: Campaign Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-primary">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Campaign Details</h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Campaign Title</label>
                        <input type="text" name="title" id="title" value="{{ $advertisement->title }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    
                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Advertisement Type</label>
                        <select name="type" id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            <option value="preventive" {{ $advertisement->type == 'preventive' ? 'selected' : '' }}>Preventive Care</option>
                            <option value="health_tip" {{ $advertisement->type == 'health_tip' ? 'selected' : '' }}>Health Tip</option>
                            <option value="service_promotion" {{ $advertisement->type == 'service_promotion' ? 'selected' : '' }}>Service Promotion</option>
                            <option value="doctor_highlight" {{ $advertisement->type == 'doctor_highlight' ? 'selected' : '' }}>Doctor Highlight</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ad Content / Message</label>
                        <textarea name="content" id="content" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>{{ $advertisement->content }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="cta_text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Call to Action (Button Text)</label>
                            <input type="text" name="cta_text" id="cta_text" value="{{ $advertisement->cta_text }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div>
                            <label for="cta_link" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Button Link (Route or URL)</label>
                            <input type="text" name="cta_link" id="cta_link" value="{{ $advertisement->cta_link }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Targeting -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-info">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Audience Targeting</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="target_gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Target Gender</label>
                        <select name="target_gender" id="target_gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="all" {{ $advertisement->target_gender == 'all' ? 'selected' : '' }}>All Genders</option>
                            <option value="male" {{ $advertisement->target_gender == 'male' ? 'selected' : '' }}>Male Only</option>
                            <option value="female" {{ $advertisement->target_gender == 'female' ? 'selected' : '' }}>Female Only</option>
                        </select>
                    </div>
                    <div>
                        <label for="target_age_min" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Min Age</label>
                        <input type="number" name="target_age_min" id="target_age_min" value="{{ $advertisement->target_age_min }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div>
                        <label for="target_age_max" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Age</label>
                        <input type="number" name="target_age_max" id="target_age_max" value="{{ $advertisement->target_age_max }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Target Medical Conditions</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50 p-3 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        @php $selectedConditions = $advertisement->target_conditions ?? []; @endphp
                        @foreach(['Hypertension', 'Diabetes', 'Asthma', 'Heart Disease', 'Allergies', 'Pregnancy', 'Obesity', 'Smoking'] as $condition)
                        <div class="flex items-center">
                            <input id="cond_{{ Str::slug($condition) }}" type="checkbox" name="target_conditions[]" value="{{ $condition }}" 
                                {{ in_array($condition, $selectedConditions) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="cond_{{ Str::slug($condition) }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $condition }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-6">
            
            <!-- Step 3: Media & Schedule -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-success">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Media & Schedule</h3>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Campaign Image</label>
                    @if($advertisement->image_path)
                        <img src="{{ asset('storage/' . $advertisement->image_path) }}" alt="Current Image" class="w-full h-auto rounded mb-2 border border-gray-200">
                    @endif
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="image" name="image" type="file" accept="image/*">
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $advertisement->start_date->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $advertisement->end_date->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                </div>

                <div class="mb-4">
                    <label for="priority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority Level</label>
                    <div class="flex items-center space-x-4">
                        <input type="range" id="priority" name="priority" min="1" max="10" value="{{ $advertisement->priority }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                        <span class="text-sm font-bold text-primary" id="priority-val">{{ $advertisement->priority }}</span>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <input type="hidden" name="is_active" value="0">
                    <input {{ $advertisement->is_active ? 'checked' : '' }} id="is_active" type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_active" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Active</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-2">
                <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                    Update Campaign
                </button>
                <a href="{{ route('admin.advertisements.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 w-full">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.getElementById('priority').addEventListener('input', function() {
        document.getElementById('priority-val').textContent = this.value;
    });
</script>
@endpush
@endsection
