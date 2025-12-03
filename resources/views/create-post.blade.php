@extends('layouts.app')

@php
    $pageTitle = 'Create New Post';
    $pageDescription = 'Submit your post for review by our team.';
@endphp

@section('content')
<!-- Cache buster: {{ now()->timestamp }} -->
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-white border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Create New Post</h1>
                        <p class="text-gray-600 mt-1">Fill out the form below to submit your post</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-900">Step {{ $currentStep ?? 1 }} of 4</div>
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="mx-8 mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Progress Bar -->
            <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    <span class="text-sm font-medium text-gray-700">
                        @if(request('title') && request('description') && request('age') && request('email') && request('phone'))
                            100%
                        @elseif(request('category_id'))
                            75%
                        @elseif(request('city_id') || $selectedCity)
                            50%
                        @elseif(request('country_id') || $selectedCountry)
                            25%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                         style="width:
                         @if(request('title') && request('description') && request('age') && request('email') && request('phone')) 100%
                         @elseif(request('category_id')) 75%
                         @elseif(request('city_id') || $selectedCity) 50%
                         @elseif(request('country_id') || $selectedCountry) 25%
                         @else 0%
                         @endif">
                    </div>
                </div>
                <div class="flex justify-between mt-6">
                    @php
                        $currentStep = 1;
                        if (request('country_id') || $selectedCountry) $currentStep = 2;
                        if (request('city_id') || $selectedCity) $currentStep = 3;
                        if (request('category_id')) $currentStep = 4;
                        if (request('title') && request('description') && request('age') && request('email') && request('phone')) $currentStep = 5;
                    @endphp

                    <div class="flex space-x-8">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                1
                            </div>
                            <span class="text-xs mt-2 font-medium {{ $currentStep >= 1 ? 'text-blue-600' : 'text-gray-500' }}">Location</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                2
                            </div>
                            <span class="text-xs mt-2 font-medium {{ $currentStep >= 3 ? 'text-blue-600' : 'text-gray-500' }}">Category</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $currentStep >= 4 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                3
                            </div>
                            <span class="text-xs mt-2 font-medium {{ $currentStep >= 4 ? 'text-blue-600' : 'text-gray-500' }}">Details</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $currentStep >= 5 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                ✓
                            </div>
                            <span class="text-xs mt-2 font-medium {{ $currentStep >= 5 ? 'text-green-600' : 'text-gray-500' }}">Complete</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                @csrf

                <!-- Step Content -->
                <div class="px-8 py-8">
                    @if($currentStep == 1)
                        <!-- Step 1: Location Selection -->
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Select Location</h2>
                                <p class="text-gray-600">Choose where your post should appear</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                    <select name="country_id" id="country_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ (request('country_id') == $country->id || ($selectedCountry && $selectedCountry->id == $country->id)) ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                    <select name="state_id" id="state_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" {{ empty($states) ? 'disabled' : '' }}>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ (request('state_id') == $state->id || ($selectedCity && $selectedCity->state_id == $state->id)) ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                    <select name="city_id" id="city_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" {{ empty($cities) ? 'disabled' : '' }}>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ (request('city_id') == $city->id || ($selectedCity && $selectedCity->id == $city->id)) ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if($selectedCity && $selectedCity->isPaid())
                                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex items-center">
                                        <div class="text-yellow-800 text-sm">
                                            This location requires payment to post.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @elseif($currentStep == 3)
                        <!-- Step 2: Category Selection -->
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Select Category</h2>
                                <p class="text-gray-600">Choose the section and category for your post</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                                    <select name="section_id" id="section_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" {{ empty($categories) ? 'disabled' : '' }}>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    @elseif($currentStep == 4)
                        <!-- Step 3: Post Details -->
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Post Details</h2>
                                <p class="text-gray-600">Fill in the information below</p>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                        <input type="text" name="title" value="{{ old('title', request('title')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" placeholder="Enter a descriptive title">
                                        @error('title') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                                        <input type="number" name="age" value="{{ old('age', request('age')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" placeholder="Your age">
                                        @error('age') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" placeholder="Provide detailed information about your post">{{ old('description', request('description')) }}</textarea>
                                    @error('description') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', request('email')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" placeholder="your.email@example.com">
                                        @error('email') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                        <input type="tel" name="phone" value="{{ old('phone', request('phone')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" placeholder="+1 (555) 123-4567">
                                        @error('phone') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Photos & Videos</label>
                                    <input type="file" name="media[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" accept="image/*,video/*">
                                    <p class="text-sm text-gray-500 mt-1">You can upload multiple images or videos (max 10MB each)</p>
                                    @error('media.*') <span class="block mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                    @elseif($currentStep == 5)
                        <!-- Step 4: Review & Submit -->
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Review & Submit</h2>
                                <p class="text-gray-600">Please review your information and submit</p>
                            </div>

                            @php
                                $reviewFields = [
                                    'country_id' => request('country_id'),
                                    'state_id' => request('state_id'),
                                    'city_id' => request('city_id'),
                                    'section_id' => request('section_id'),
                                    'category_id' => request('category_id'),
                                    'title' => request('title'),
                                    'description' => request('description'),
                                    'age' => request('age'),
                                    'email' => request('email'),
                                    'phone' => request('phone'),
                                ];
                            @endphp

                            @foreach($reviewFields as $fieldName => $fieldValue)
                                <input type="hidden" name="{{ $fieldName }}" value="{{ $fieldValue }}">
                            @endforeach

                            <div class="bg-green-50 border border-green-200 rounded-md p-6 mb-6">
                                <div class="text-center">
                                    <div class="text-green-800 text-sm">
                                        Your post is ready for submission. It will be reviewed before publication.
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-md transition duration-200">
                                    Submit Post
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Navigation Buttons -->
                @if($currentStep < 5)
                    <div class="flex justify-between px-8 py-6 bg-gray-50 border-t">
                        <button type="button" onclick="goToPreviousStep()" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition duration-200 {{ $currentStep == 1 ? 'invisible' : '' }}">
                            Previous
                        </button>
                        <button type="button" onclick="goToNextStep()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-200">
                            Next
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
let currentStep = {{ $currentStep }};

// Store all available options
let allStates = @json($countries->pluck('states')->flatten());
let allCities = @json($countries->pluck('states')->flatten()->pluck('cities')->flatten());

// Build categories array with section_id
let allCategories = [];
@foreach($sections as $section)
    @foreach($section->categories as $category)
        allCategories.push({
            id: {{ $category->id }},
            name: "{{ $category->name }}",
            section_id: {{ $section->id }}
        });
    @endforeach
@endforeach

console.log('All Categories:', allCategories);

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dropdowns based on current selections
    initializeDropdowns();
    
    const countrySelect = document.getElementById('country_id');
    if (countrySelect) {
        // Country change handler - populate states
        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');

            if (!stateSelect || !citySelect) {
                return;
            }

            // Clear and disable dependent selects
            stateSelect.innerHTML = '<option value="">Select State</option>';
            citySelect.innerHTML = '<option value="">Select City</option>';
            citySelect.disabled = true;

            if (countryId) {
                // Populate states for selected country
                const countryStates = allStates.filter(state => state.country_id == countryId);
                countryStates.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });
                stateSelect.disabled = false;
            } else {
                stateSelect.disabled = true;
            }
        });
    }

    const stateSelect = document.getElementById('state_id');
    if (stateSelect) {
        // State change handler - populate cities
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            const citySelect = document.getElementById('city_id');

            if (!citySelect) {
                return;
            }

            // Clear city select
            citySelect.innerHTML = '<option value="">Select City</option>';

            if (stateId) {
                // Populate cities for selected state
                const stateCities = allCities.filter(city => city.state_id == stateId);
                stateCities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
                citySelect.disabled = false;
            } else {
                citySelect.disabled = true;
            }
        });
    }

    // Section change handler - populate categories
    const sectionSelect = document.getElementById('section_id');
    if (sectionSelect) {
        sectionSelect.addEventListener('change', function() {
            const sectionId = parseInt(this.value);
            const categorySelect = document.getElementById('category_id');

            console.log('Section selected:', sectionId);
            console.log('Category select element:', categorySelect);
            console.log('All categories:', allCategories);

            if (!categorySelect) {
                console.log('Category select not found!');
                return;
            }

            // Clear category select
            categorySelect.innerHTML = '<option value="">Select Category</option>';

            if (sectionId) {
                // Populate categories for selected section
                const sectionCategories = allCategories.filter(category => {
                    console.log('Comparing:', category.section_id, 'with', sectionId);
                    return category.section_id == sectionId;
                });
                console.log('Filtered categories:', sectionCategories);
                
                sectionCategories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
                categorySelect.disabled = false;
            } else {
                categorySelect.disabled = true;
            }
        });
    }
});

function initializeDropdowns() {
    // Trigger change events to populate dependent dropdowns on page load
    const countrySelect = document.getElementById('country_id');
    if (countrySelect && countrySelect.value) {
        const event = new Event('change');
        countrySelect.dispatchEvent(event);
        
        // Then trigger state change if state is selected
        setTimeout(() => {
            const stateSelect = document.getElementById('state_id');
            if (stateSelect && stateSelect.value) {
                const stateEvent = new Event('change');
                stateSelect.dispatchEvent(stateEvent);
            }
        }, 100);
    }
    
    // Initialize section/category dropdowns
    const sectionSelect = document.getElementById('section_id');
    if (sectionSelect) {
        if (sectionSelect.value) {
            const event = new Event('change');
            sectionSelect.dispatchEvent(event);
        }
    }
}

function goToNextStep() {
    // Validate current step before proceeding
    if (currentStep === 1) {
        const cityId = document.getElementById('city_id').value;
        if (!cityId) {
            alert('Please select a city before proceeding.');
            return;
        }
    } else if (currentStep === 3) {
        const categoryId = document.getElementById('category_id').value;
        if (!categoryId) {
            alert('Please select a category before proceeding.');
            return;
        }
    } else if (currentStep === 4) {
        // Validate post details
        const title = document.querySelector('input[name="title"]').value;
        const description = document.querySelector('textarea[name="description"]').value;
        const age = document.querySelector('input[name="age"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const phone = document.querySelector('input[name="phone"]').value;
        
        if (!title || !description || !age || !email || !phone) {
            alert('Please fill in all required fields before proceeding.');
            return;
        }
    }
    
    if (currentStep < 5) {
        currentStep++;
        if (currentStep === 2) currentStep = 3; // Skip step 2 (goes from 1 to 3)
        updateStepDisplay();
    }
}

function goToPreviousStep() {
    if (currentStep > 1) {
        currentStep--;
        if (currentStep === 2) currentStep = 1; // Skip step 2 (goes from 3 to 1)
        updateStepDisplay();
    }
}

function updateStepDisplay() {
    // Build URL with current form data
    const url = new URL(window.location.href);
    const form = document.getElementById('postForm');
    const formData = new FormData(form);
    
    // Add all form fields to URL
    for (let [key, value] of formData.entries()) {
        if (value && key !== 'media[]') {
            url.searchParams.set(key, value);
        }
    }
    
    // Reload page with updated parameters
    window.location.href = url.toString();
}
</script>
@endsection
