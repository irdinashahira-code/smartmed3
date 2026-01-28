@extends('layouts.admin_nextkit')

@section('title', 'Create Campaign')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
        <a href="{{ route('admin.advertisements.index') }}" class="hover:text-primary">Advertisements</a>
        <i class="bi bi-chevron-right text-xs"></i>
        <span>Create Campaign</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">New Ad Campaign Wizard</h1>
</div>

<form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Step 1: Campaign Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-primary">
                <div class="flex items-center mb-4">
                    <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold mr-3">1</span>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Campaign Details</h3>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Campaign Title</label>
                        <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="e.g., Annual Health Screening Promo" required>
                    </div>
                    
                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Advertisement Type</label>
                        <select name="type" id="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            <option value="preventive">Preventive Care (e.g., Screening, Checkups)</option>
                            <option value="health_tip">Health Tip (e.g., Wellness Advice)</option>
                            <option value="service_promotion">Service Promotion (e.g., New Treatments)</option>
                            <option value="doctor_highlight">Doctor Highlight</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ad Content / Message</label>
                        <textarea name="content" id="content" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter the main text for your advertisement..." required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="cta_text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Call to Action (Button Text)</label>
                            <input type="text" name="cta_text" id="cta_text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="e.g., Book Now">
                        </div>
                        <div>
                            <label for="cta_link" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Button Link (Route or URL)</label>
                            <input type="text" name="cta_link" id="cta_link" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="e.g., /patient/book-appointment">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Targeting -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-info">
                <div class="flex items-center mb-4">
                    <span class="w-8 h-8 rounded-full bg-info text-white flex items-center justify-center font-bold mr-3">2</span>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Audience Targeting</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="target_gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Target Gender</label>
                        <select name="target_gender" id="target_gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="all">All Genders</option>
                            <option value="male">Male Only</option>
                            <option value="female">Female Only</option>
                        </select>
                    </div>
                    <div>
                        <label for="target_age_min" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Min Age</label>
                        <input type="number" name="target_age_min" id="target_age_min" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0">
                    </div>
                    <div>
                        <label for="target_age_max" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Age</label>
                        <input type="number" name="target_age_max" id="target_age_max" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="100">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Target Medical Conditions (Optional)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50 p-3 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        <!-- Common conditions checkbox list -->
                        @foreach(['Hypertension', 'Diabetes', 'Asthma', 'Heart Disease', 'Allergies', 'Pregnancy', 'Obesity', 'Smoking'] as $condition)
                        <div class="flex items-center">
                            <input id="cond_{{ Str::slug($condition) }}" type="checkbox" name="target_conditions[]" value="{{ $condition }}" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="cond_{{ Str::slug($condition) }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $condition }}</label>
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Leave unchecked to target all patients regardless of conditions.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-6">
            
            <!-- Step 3: Media & Schedule -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-success">
                <div class="flex items-center mb-4">
                    <span class="w-8 h-8 rounded-full bg-success text-white flex items-center justify-center font-bold mr-3">3</span>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Media & Schedule</h3>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Upload Image</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="image" name="image" type="file" accept="image/*">
                    <p class="mt-1 text-xs text-gray-500">Recommended size: 800x400px. Max 2MB.</p>
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-4">
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                </div>

                <div class="mb-4">
                    <label for="priority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority Level</label>
                    <div class="flex items-center space-x-4">
                        <input type="range" id="priority" name="priority" min="1" max="10" value="5" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                        <span class="text-sm font-bold text-primary" id="priority-val">5</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Higher priority ads show first (1-10).</p>
                </div>

                <div class="flex items-center mb-4">
                    <input checked id="is_active" type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="is_active" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Active Immediately</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-2">
                <button type="submit" class="text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">
                    Launch Campaign
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
