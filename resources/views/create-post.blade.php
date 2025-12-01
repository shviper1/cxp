@extends('layouts.app')

@php
    $pageTitle = 'Create New Post';
    $pageDescription = 'Submit your post for review by our team.';
@endphp

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Create New Post</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-600">Progress</span>
                <span class="text-sm font-medium text-gray-600">
                    @if(request('title') && request('description') && request('age') && request('email') && request('phone'))
                        100%
                    @elseif(request('category_id'))
                        67%
                    @elseif(request('city_id') || $selectedCity)
                        33%
                    @elseif(request('country_id') || $selectedCountry)
                        10%
                    @else
                        0%
                    @endif
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out"
                     style="width:
                     @if(request('title') && request('description') && request('age') && request('email') && request('phone')) 100%
                     @elseif(request('category_id')) 67%
                     @elseif(request('city_id') || $selectedCity) 33%
                     @elseif(request('country_id') || $selectedCountry) 10%
                     @else 0%
                     @endif">
                </div>
            </div>
            <div class="flex justify-between mt-3">
                @php
                    $step1Active = (!request('city_id') && !$selectedCity);
                    $step1Completed = request('country_id') || $selectedCountry;
                    $step2Active = (request('city_id') || $selectedCity) && !request('category_id');
                    $step2Completed = request('category_id');
                    $step3Active = request('category_id') && (!request('title') || !request('description') || !request('age') || !request('email') || !request('phone'));
                    $step3Completed = request('title') && request('description') && request('age') && request('email') && request('phone');
                    $step4Active = $step3Completed;
                @endphp

                <button type="button" onclick="showStep(1)"
                        class="flex flex-col items-center text-xs transition-all duration-200 {{ $step1Active ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-200 {{ $step1Active ? 'bg-indigo-600 text-white' : ($step1Completed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                        <span class="text-xs font-bold">1</span>
                    </div>
                    <span>Location</span>
                </button>

                <button type="button" onclick="showStep(2)"
                        class="flex flex-col items-center text-xs transition-all duration-200 {{ $step2Active ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}"
                        {{ $step1Active ? 'disabled' : '' }}>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-200 {{ $step2Active ? 'bg-indigo-600 text-white' : ($step2Completed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                        <span class="text-xs font-bold">2</span>
                    </div>
                    <span>Category</span>
                </button>

                <button type="button" onclick="showStep(3)"
                        class="flex flex-col items-center text-xs transition-all duration-200 {{ $step3Active ? 'text-indigo-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}"
                        {{ !request('category_id') ? 'disabled' : '' }}>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-200 {{ $step3Active ? 'bg-indigo-600 text-white' : ($step3Completed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                        <span class="text-xs font-bold">3</span>
                    </div>
                    <span>Details</span>
                </button>

                <button type="button" onclick="showStep(4)"
                        class="flex flex-col items-center text-xs transition-all duration-200 {{ $step4Active ? 'text-green-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}"
                        {{ !request('category_id') ? 'disabled' : '' }}>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-200 {{ $step4Active ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                        <span class="text-xs font-bold">✓</span>
                    </div>
                    <span>Complete</span>
                </button>
            </div>
        </div>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Step 1: Location Section -->
            @php
                $step1IsActive = (!request('city_id') && !$selectedCity);
                $step1StatusClass = $step1IsActive ? 'bg-indigo-600 text-white' : ((request('country_id') || $selectedCountry) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600');
            @endphp
            <div class="accordion-section bg-white border border-gray-200 rounded-lg overflow-hidden {{ $step1IsActive ? 'active' : '' }}">
                <button type="button" onclick="toggleSection(1)" class="w-full text-left accordion-header bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 hover:from-blue-100 hover:to-indigo-100 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-all duration-200 {{ $step1StatusClass }}">
                                <span class="text-sm font-bold">1</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Location</h3>
                                <p class="text-sm text-gray-600">📍 Select where your post should appear</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 accordion-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
                <div class="accordion-content px-6 pb-6 {{ (!request('city_id') && !$selectedCity) ? 'block' : 'hidden' }}">
                    <div class="pt-4 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                        <select name="country_id" id="country_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ (request('country_id') == $country->id || ($selectedCountry && $selectedCountry->id == $country->id)) ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                        <select name="state_id" id="state_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ (request('state_id') == $state->id || ($selectedCity && $selectedCity->state_id == $state->id)) ? 'selected' : '' }}>{{ $state->name }}</option>
                            @endforeach
                        </select>
                        @error('state_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                        <select name="city_id" id="city_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ (request('city_id') == $city->id || ($selectedCity && $selectedCity->id == $city->id)) ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('city_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                </div>
            </div>

            <!-- Step 2: Category Section -->
            @php
                $step2IsActive = (request('city_id') || $selectedCity) && !request('category_id');
                $step2Disabled = (!request('city_id') && !$selectedCity);
                $step2StatusClass = $step2IsActive ? 'bg-indigo-600 text-white' : (request('category_id') ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600');
            @endphp
            <div class="accordion-section bg-white border border-gray-200 rounded-lg overflow-hidden {{ $step2IsActive ? 'active' : '' }}">
                <button type="button" onclick="toggleSection(2)" class="w-full text-left accordion-header bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 hover:from-green-100 hover:to-emerald-100 transition-all duration-200 {{ $step2Disabled ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $step2Disabled ? 'disabled' : '' }}>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-all duration-200 {{ $step2StatusClass }}">
                                <span class="text-sm font-bold">2</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Category</h3>
                                <p class="text-sm text-gray-600">📂 Choose section and category</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 accordion-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
                <div class="accordion-content px-6 pb-6 {{ (request('city_id') || $selectedCity) && !request('category_id') ? 'block' : 'hidden' }}">
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-4">📂 Choose the section and specific category that best fits your post.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                        <select name="section_id" id="section_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('section_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Post Details & Media Section -->
            @php
                $step3IsActive = request('category_id') && (!request('title') || !request('description') || !request('age') || !request('email') || !request('phone'));
                $step3Disabled = !request('category_id');
                $step3StatusClass = $step3IsActive ? 'bg-indigo-600 text-white' : ((request('title') && request('description') && request('age') && request('email') && request('phone')) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600');
            @endphp
            <div class="accordion-section bg-white border border-gray-200 rounded-lg overflow-hidden {{ $step3IsActive ? 'active' : '' }}">
                <button type="button" onclick="toggleSection(3)" class="w-full text-left accordion-header bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 hover:from-purple-100 hover:to-pink-100 transition-all duration-200 {{ $step3Disabled ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $step3Disabled ? 'disabled' : '' }}>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-all duration-200 {{ $step3StatusClass }}">
                                <span class="text-sm font-bold">3</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Post Details & Media</h3>
                                <p class="text-sm text-gray-600">📝 Complete your post information</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 accordion-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
                <div class="accordion-content px-6 pb-6 {{ request('category_id') && (!request('title') || !request('description') || !request('age') || !request('email') || !request('phone')) ? 'block' : 'hidden' }}">
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-6">📝 Complete all the information below to create your post.</p>

                <!-- Post Details Fields -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" value="{{ old('title', request('title')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter a descriptive title for your post">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Age *</label>
                            <input type="number" name="age" value="{{ old('age', request('age')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Your age">
                            @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="5" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Provide detailed information about your post">{{ old('description', request('description')) }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email', request('email')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="your.email@example.com">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">We'll use this to contact you about your post</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                            <input type="tel" name="phone" value="{{ old('phone', request('phone')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="+1 (555) 123-4567">
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Photos & Videos Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photos & Videos</label>
                        <input type="file" name="media[]" multiple class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" accept="image/*,video/*">
                        <p class="text-sm text-gray-500 mt-1">You can upload multiple images or videos (max 10MB each). Leave empty if not needed.</p>
                        @error('media.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info (if applicable) -->
            @if($selectedCity && $selectedCity->isPaid())
                @php
                    $country = $selectedCity->state->country;
                    $amount = $country->amount ?? 0;
                    $currency = $country->currency_symbol ?? '$';
                @endphp
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-800">Paid Post Required</h3>
                            <p class="text-yellow-700">This location requires paid posts. Amount: <strong>{{ $currency }} {{ number_format($amount, 2) }}</strong></p>
                            <p class="text-sm text-yellow-600 mt-1">Payment will be processed after submission.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Section -->
            @php
                $step4IsActive = (request('title') && request('description') && request('age') && request('email') && request('phone'));
                $step4Disabled = !request('category_id');
                $step4StatusClass = $step4IsActive ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600';
            @endphp
            <div class="accordion-section bg-white border border-gray-200 rounded-lg overflow-hidden {{ $step4IsActive ? 'active' : '' }}">
                <button type="button" onclick="toggleSection(4)" class="w-full text-left accordion-header bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 hover:from-green-100 hover:to-teal-100 transition-all duration-200 {{ $step4Disabled ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $step4Disabled ? 'disabled' : '' }}>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 transition-all duration-200 {{ $step4StatusClass }}">
                                <span class="text-sm font-bold">✓</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Ready to Submit</h3>
                                <p class="text-sm text-gray-600">Review and submit your post</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-500 accordion-icon transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
                <div class="accordion-content px-6 pb-6 {{ $step4IsActive ? 'block' : 'hidden' }}">
                    <div class="pt-4 border-t border-gray-100">
                        <div class="text-center">
                            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-green-800">All Set!</h3>
                                </div>
                                <p class="text-green-700">Your post is ready for submission. Review the details above and click submit when ready.</p>
                            </div>

                            <button type="submit" class="bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-bold py-4 px-12 rounded-xl shadow-xl transition duration-300 transform hover:scale-105 flex items-center space-x-2 mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>🚀 Submit Your Post</span>
                            </button>

                            <p class="text-center text-sm text-gray-600 mt-4">
                                Your post will be reviewed by our team before being published
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentStep = 1;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize accordion based on current progress
    initializeAccordion();

    // Country change handler - reload page with selected country
    document.getElementById('country_id').addEventListener('change', function() {
        const countryId = this.value;
        if (countryId) {
            reloadWithParams({ country_id: countryId });
        }
    });

    // State change handler - reload page with selected state
    document.getElementById('state_id').addEventListener('change', function() {
        const stateId = this.value;
        const countryId = document.getElementById('country_id').value;
        if (stateId && countryId) {
            reloadWithParams({
                country_id: countryId,
                state_id: stateId
            });
        }
    });

    // City change handler - reload page with selected city
    document.getElementById('city_id').addEventListener('change', function() {
        const cityId = this.value;
        const countryId = document.getElementById('country_id').value;
        const stateId = document.getElementById('state_id').value;
        if (cityId && stateId && countryId) {
            reloadWithParams({
                country_id: countryId,
                state_id: stateId,
                city_id: cityId
            });
        }
    });

    // Section change handler - reload page with selected section
    document.getElementById('section_id').addEventListener('change', function() {
        const sectionId = this.value;
        const countryId = document.getElementById('country_id').value;
        const stateId = document.getElementById('state_id').value;
        const cityId = document.getElementById('city_id').value;
        if (sectionId && cityId && stateId && countryId) {
            reloadWithParams({
                country_id: countryId,
                state_id: stateId,
                city_id: cityId,
                section_id: sectionId
            });
        }
    });

    // Category change handler - update URL and show next step
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        if (categoryId) {
            const url = new URL(window.location);
            url.searchParams.set('category_id', categoryId);
            window.history.replaceState({}, '', url.toString());

            // Auto-expand next step
            setTimeout(() => {
                showStep(3);
            }, 300);
        }
    });
});

function initializeAccordion() {
    // Determine current step based on form progress
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('title') && urlParams.get('description') && urlParams.get('age') && urlParams.get('email') && urlParams.get('phone')) {
        currentStep = 4;
    } else if (urlParams.get('category_id')) {
        currentStep = 3;
    } else if (urlParams.get('city_id')) {
        currentStep = 2;
    } else if (urlParams.get('country_id')) {
        currentStep = 1;
    }

    showStep(currentStep);
}

function showStep(stepNumber) {
    // Hide all sections
    document.querySelectorAll('.accordion-section').forEach(section => {
        section.classList.remove('active');
        const content = section.querySelector('.accordion-content');
        const icon = section.querySelector('.accordion-icon');

        if (content) content.classList.add('hidden');
        if (icon) icon.classList.remove('rotate-180');
    });

    // Show selected section
    const targetSection = document.querySelectorAll('.accordion-section')[stepNumber - 1];
    if (targetSection) {
        targetSection.classList.add('active');
        const content = targetSection.querySelector('.accordion-content');
        const icon = targetSection.querySelector('.accordion-icon');

        if (content) content.classList.remove('hidden');
        if (icon) icon.classList.add('rotate-180');

        currentStep = stepNumber;
    }
}

function toggleSection(stepNumber) {
    const targetSection = document.querySelectorAll('.accordion-section')[stepNumber - 1];
    if (!targetSection) return;

    const isActive = targetSection.classList.contains('active');

    // Check if section can be opened
    if (stepNumber === 2 && !document.getElementById('city_id').value) return;
    if (stepNumber === 3 && !document.getElementById('category_id').value) return;
    if (stepNumber === 4 && !isFormComplete()) return;

    if (isActive) {
        // If clicking on active section, close all
        document.querySelectorAll('.accordion-section').forEach(section => {
            section.classList.remove('active');
            const content = section.querySelector('.accordion-content');
            const icon = section.querySelector('.accordion-icon');
            if (content) content.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        });
    } else {
        showStep(stepNumber);
    }
}

function reloadWithParams(params) {
    const url = new URL(window.location);

    // Clear dependent parameters
    if (params.country_id && !params.state_id) {
        url.searchParams.delete('state_id');
        url.searchParams.delete('city_id');
        url.searchParams.delete('section_id');
        url.searchParams.delete('category_id');
    }
    if (params.state_id && !params.city_id) {
        url.searchParams.delete('city_id');
        url.searchParams.delete('section_id');
        url.searchParams.delete('category_id');
    }
    if (params.city_id && !params.section_id) {
        url.searchParams.delete('section_id');
        url.searchParams.delete('category_id');
    }
    if (params.section_id && !params.category_id) {
        url.searchParams.delete('category_id');
    }

    // Set new parameters
    Object.keys(params).forEach(key => {
        url.searchParams.set(key, params[key]);
    });

    window.location.href = url.toString();
}

function isFormComplete() {
    const title = document.querySelector('input[name="title"]').value;
    const description = document.querySelector('textarea[name="description"]').value;
    const age = document.querySelector('input[name="age"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;

    return title && description && age && email && phone;
}

// Auto-advance to next step when form fields are filled
document.addEventListener('input', function(e) {
    if (isFormComplete() && currentStep === 3) {
        setTimeout(() => {
            showStep(4);
        }, 500);
    }
});
</script>

<style>
.rotate-180 {
    transform: rotate(180deg);
}

.accordion-icon {
    transition: transform 0.2s ease;
}

.accordion-section.active .accordion-icon {
    transform: rotate(180deg);
}

.accordion-content {
    transition: all 0.3s ease;
}
</style>
@endsection
