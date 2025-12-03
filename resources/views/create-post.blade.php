@extends('layouts.app')

@php
    $pageTitle = 'Create New Post';
    $pageDescription = 'Submit your post for review by our team.';
    $siteTitle = $siteSettings['site_name'] ?? config('app.name', 'CX Platform');
@endphp

@section('title', $pageTitle . ' | ' . $siteTitle)
@section('meta_description', $pageDescription)
@section('meta_keywords', 'create post, submit listing, classifieds')

@section('content')
    @php
        $basicTitle = old('title', request('title'));
        $basicDescription = old('description', request('description'));
        $basicAge = old('age', request('age'));

        $chosenCountryId = request('country_id') ?? optional($selectedCountry)->id;
        $chosenStateId = request('state_id') ?? optional($selectedCity)->state_id;
        $chosenCityId = request('city_id') ?? optional($selectedCity)->id;

        $contactEmail = old('email', request('email'));
        $contactPhone = old('phone', request('phone'));

        $chosenSectionId = request('section_id');
        $chosenCategoryId = request('category_id');

        $basicCompleted = filled($basicTitle) && filled($basicDescription) && filled($basicAge);
        $locationCompleted = filled($chosenCountryId) && filled($chosenStateId) && filled($chosenCityId);
        $contactCompleted = filled($contactEmail) && filled($contactPhone);
        $categoryCompleted = filled($chosenSectionId) && filled($chosenCategoryId);

        if (!$basicCompleted) {
            $currentStep = 1;
        } elseif (!$locationCompleted) {
            $currentStep = 2;
        } elseif (!$contactCompleted) {
            $currentStep = 3;
        } elseif (!$categoryCompleted) {
            $currentStep = 4;
        } else {
            $currentStep = 5;
        }

        $stepLabels = [
            1 => ['title' => 'Basic Information', 'subtitle' => 'Tell us about your post'],
            2 => ['title' => 'Location Details', 'subtitle' => 'Where is this located?'],
            3 => ['title' => 'Contact Information', 'subtitle' => 'How can people reach you?'],
            4 => ['title' => 'Category & Media', 'subtitle' => 'Categorize and add photos'],
        ];

        $completedCount = collect([$basicCompleted, $locationCompleted, $contactCompleted, $categoryCompleted])
            ->filter()
            ->count();
        $progressPercent = $currentStep === 5 ? 100 : ($completedCount / 4) * 100;
        $progressPercent = max(0, min(100, round($progressPercent)));
        $displayStep = min($currentStep, 4);
    @endphp
    <!-- Cache buster: {{ now()->timestamp }} -->
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="max-w-4xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white rounded-lg shadow-lg">
                <!-- Header -->
                <div class="px-8 py-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Create New Post</h1>
                            <p class="mt-1 text-gray-600">Fill out the form below to submit your post</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">Step {{ $displayStep }} of 4</div>
                        </div>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="px-4 py-3 mx-8 mt-6 text-green-700 border border-green-200 rounded bg-green-50">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Progress Bar -->
                <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-medium text-gray-700">{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 transition-all duration-500 bg-blue-600 rounded-full"
                            style="width: {{ $progressPercent }}%">
                        </div>
                    </div>
                    <div class="flex flex-col gap-6 mt-6">
                        <div class="flex flex-wrap gap-8">
                            @foreach ($stepLabels as $index => $step)
                                @php
                                    $isCompleted = $currentStep > $index;
                                    $isCurrent = $currentStep === $index;
                                    $circleClasses = $isCompleted
                                        ? 'bg-green-600 text-white'
                                        : ($isCurrent
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-gray-200 text-gray-600');
                                    $labelClasses = $isCompleted || $isCurrent ? 'text-blue-600' : 'text-gray-500';
                                @endphp
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $circleClasses }}">
                                        {{ $index }}
                                    </div>
                                    <span class="mt-2 text-xs font-semibold uppercase tracking-wide {{ $labelClasses }}">
                                        {{ $step['title'] }}
                                    </span>
                                    <p class="mt-1 text-[11px] text-slate-500 max-w-[140px]">{{ $step['subtitle'] }}</p>
                                </div>
                            @endforeach
                            <div class="flex flex-col items-center text-center">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $currentStep === 5 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                    ✓
                                </div>
                                <span
                                    class="mt-2 text-xs font-semibold uppercase tracking-wide {{ $currentStep === 5 ? 'text-green-600' : 'text-gray-500' }}">
                                    Review
                                </span>
                                <p class="mt-1 text-[11px] text-slate-500 max-w-[140px]">Preview &amp; submit</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                    @csrf

                    <!-- Step Content -->
                    <div class="px-8 py-8">
                        @if ($currentStep === 1)
                            <!-- Step 1: Basic Information -->
                            <div class="max-w-2xl mx-auto">
                                <div class="mb-8 text-center">
                                    <h2 class="mb-2 text-xl font-semibold text-gray-900">Basic Information</h2>
                                    <p class="text-gray-600">Tell us about your post</p>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Post Title <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="title" value="{{ $basicTitle }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Enter a descriptive title">
                                        @error('title')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Description <span
                                                class="text-red-500">*</span></label>
                                        <textarea name="description" rows="5"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="Provide detailed information about your post">{{ $basicDescription }}</textarea>
                                        @error('description')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-700">Age <span
                                                    class="text-red-500">*</span></label>
                                            <input type="number" name="age" value="{{ $basicAge }}" min="1"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                                placeholder="Your age">
                                            @error('age')
                                                <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($currentStep === 2)
                            <!-- Step 2: Location Details -->
                            <div class="max-w-2xl mx-auto">
                                <div class="mb-8 text-center">
                                    <h2 class="mb-2 text-xl font-semibold text-gray-900">Location Details</h2>
                                    <p class="text-gray-600">Where is this located?</p>
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Country <span
                                                class="text-red-500">*</span></label>
                                        <select name="country_id" id="country_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ $chosenCountryId == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">State <span
                                                class="text-red-500">*</span></label>
                                        <select name="state_id" id="state_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            {{ empty($states) ? 'disabled' : '' }}>
                                            <option value="">Select State</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ $chosenStateId == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">City <span
                                                class="text-red-500">*</span></label>
                                        <select name="city_id" id="city_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            {{ empty($cities) ? 'disabled' : '' }}>
                                            <option value="">Select City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ $chosenCityId == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                @if ($selectedCity && $selectedCity->isPaid())
                                    <div class="p-4 mt-6 border border-yellow-200 rounded-md bg-yellow-50">
                                        <div class="flex items-center">
                                            <div class="text-sm text-yellow-800">
                                                This location requires payment to post.
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif($currentStep === 3)
                            <!-- Step 3: Contact Information -->
                            <div class="max-w-2xl mx-auto">
                                <div class="mb-8 text-center">
                                    <h2 class="mb-2 text-xl font-semibold text-gray-900">Contact Information</h2>
                                    <p class="text-gray-600">How can people reach you?</p>
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Email Address <span
                                                class="text-red-500">*</span></label>
                                        <input type="email" name="email" value="{{ $contactEmail }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="your.email@example.com">
                                        @error('email')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Phone Number <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="phone" value="{{ $contactPhone }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            placeholder="+1 (555) 123-4567">
                                        @error('phone')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <p class="mt-6 text-sm text-gray-500">Your contact details are only shared with
                                    administrators for review purposes.</p>
                            </div>
                        @elseif($currentStep === 4)
                            <!-- Step 4: Category & Media -->
                            <div class="max-w-2xl mx-auto space-y-8">
                                <div class="text-center">
                                    <h2 class="mb-2 text-xl font-semibold text-gray-900">Category &amp; Media</h2>
                                    <p class="text-gray-600">Categorize your post and add supporting visuals</p>
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Section <span
                                                class="text-red-500">*</span></label>
                                        <select name="section_id" id="section_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Section</option>
                                            @foreach ($sections as $section)
                                                <option value="{{ $section->id }}"
                                                    {{ $chosenSectionId == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_id')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Category <span
                                                class="text-red-500">*</span></label>
                                        <select name="category_id" id="category_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                            {{ empty($categories) ? 'disabled' : '' }}>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $chosenCategoryId == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Photos &amp; Videos</label>
                                    <input type="file" name="media[]" multiple
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                        accept="image/*,video/*">
                                    <p class="mt-1 text-sm text-gray-500">Upload up to 10 files (images or videos, 10MB max
                                        each). Existing uploads stay attached unless you replace them.</p>
                                    @error('media.*')
                                        <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @elseif($currentStep === 5)
                            <!-- Step 5: Review & Submit -->
                            <div class="max-w-2xl mx-auto">
                                <div class="mb-8 text-center">
                                    <h2 class="mb-2 text-xl font-semibold text-gray-900">Review & Submit</h2>
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

                                @foreach ($reviewFields as $fieldName => $fieldValue)
                                    <input type="hidden" name="{{ $fieldName }}" value="{{ $fieldValue }}">
                                @endforeach

                                <div class="p-6 mb-6 border border-green-200 rounded-md bg-green-50">
                                    <div class="text-center">
                                        <div class="text-sm text-green-800">
                                            Your post is ready for submission. It will be reviewed before publication.
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit"
                                        class="px-8 py-3 font-medium text-white transition duration-200 bg-blue-600 rounded-md hover:bg-blue-700">
                                        Submit Post
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    @if ($currentStep < 5)
                        <div class="flex justify-between px-8 py-6 border-t bg-gray-50">
                            <button type="button" onclick="goToPreviousStep()"
                                class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition duration-200 {{ $currentStep == 1 ? 'invisible' : '' }}">
                                Previous
                            </button>
                            <button type="button" onclick="goToNextStep()"
                                class="px-6 py-2 font-medium text-white transition duration-200 bg-blue-600 rounded-md hover:bg-blue-700">
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
        @foreach ($sections as $section)
            @foreach ($section->categories as $category)
                allCategories.push({
                    id: {{ $category->id }},
                    name: "{{ $category->name }}",
                    section_id: {{ $section->id }}
                });
            @endforeach
        @endforeach

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

                    if (!categorySelect) {
                        return;
                    }

                    // Clear category select
                    categorySelect.innerHTML = '<option value="">Select Category</option>';

                    if (sectionId) {
                        // Populate categories for selected section
                        const sectionCategories = allCategories.filter(category => category.section_id ==
                            sectionId);

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
                const title = document.querySelector('input[name="title"]')?.value.trim();
                const description = document.querySelector('textarea[name="description"]')?.value.trim();
                const age = document.querySelector('input[name="age"]')?.value;

                if (!title || !description || !age) {
                    alert('Please complete the title, description, and age fields before continuing.');
                    return;
                }
            } else if (currentStep === 2) {
                const countryId = document.getElementById('country_id')?.value;
                const stateId = document.getElementById('state_id')?.value;
                const cityId = document.getElementById('city_id')?.value;

                if (!countryId || !stateId || !cityId) {
                    alert('Please select a country, state, and city before proceeding.');
                    return;
                }
            } else if (currentStep === 3) {
                const email = document.querySelector('input[name="email"]')?.value.trim();
                const phone = document.querySelector('input[name="phone"]')?.value.trim();

                if (!email || !phone) {
                    alert('Please provide both your email address and phone number.');
                    return;
                }
            } else if (currentStep === 4) {
                const sectionId = document.getElementById('section_id')?.value;
                const categoryId = document.getElementById('category_id')?.value;

                if (!sectionId || !categoryId) {
                    alert('Please choose a section and category before proceeding.');
                    return;
                }
            }

            if (currentStep < 5) {
                currentStep++;
                updateStepDisplay();
            }
        }

        function goToPreviousStep() {
            if (currentStep > 1) {
                currentStep--;
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
