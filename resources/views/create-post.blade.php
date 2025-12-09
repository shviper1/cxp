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
                            <div class="text-lg font-semibold text-gray-900">Step <span
                                    data-step-display>{{ $displayStep }}</span> of 4</div>
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
                        <span class="text-sm font-medium text-gray-700" data-progress-text>{{ $progressPercent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 transition-all duration-500 bg-blue-600 rounded-full" id="progressBar"
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
                                <div class="flex flex-col items-center text-center"
                                    data-step-indicator="{{ $index }}">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $circleClasses }}"
                                        data-step-circle="{{ $index }}">
                                        {{ $index }}
                                    </div>
                                    <span class="mt-2 text-xs font-semibold uppercase tracking-wide {{ $labelClasses }}"
                                        data-step-label="{{ $index }}">
                                        {{ $step['title'] }}
                                    </span>
                                    <p class="mt-1 text-[11px] text-slate-500 max-w-[140px]">{{ $step['subtitle'] }}</p>
                                </div>
                            @endforeach
                            <div class="flex flex-col items-center text-center" data-step-indicator="5">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $currentStep === 5 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}"
                                    data-step-circle="5">
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
                    <div class="px-8 py-8 space-y-10">
                        <!-- Step 1: Basic Information -->
                        <div class="max-w-2xl mx-auto step-content {{ $currentStep === 1 ? '' : 'hidden' }}"
                            data-step="1">
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

                        <!-- Step 2: Location Details -->
                        <div class="max-w-2xl mx-auto step-content {{ $currentStep === 2 ? '' : 'hidden' }}"
                            data-step="2">
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

                        <!-- Step 3: Contact Information -->
                        <div class="max-w-2xl mx-auto step-content {{ $currentStep === 3 ? '' : 'hidden' }}"
                            data-step="3">
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

                        <!-- Step 4: Category & Media -->
                        <div class="max-w-2xl mx-auto space-y-8 step-content {{ $currentStep === 4 ? '' : 'hidden' }}"
                            data-step="4">
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
                                <label class="block mb-2 text-sm font-medium text-gray-700">Photos &amp; Videos <span
                                        class="text-red-500">*</span></label>
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

                        <!-- Step 5: Review & Submit -->
                        <div class="max-w-3xl mx-auto step-content {{ $currentStep === 5 ? '' : 'hidden' }}"
                            data-step="5">
                            <div class="mb-8 text-center">
                                <h2 class="mb-2 text-xl font-semibold text-gray-900">Review &amp; Submit</h2>
                                <p class="text-gray-600">Double-check your information before publishing.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Post Status <span
                                            class="text-red-500">*</span></label>
                                    <select name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                        <option value="pending"
                                            {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending Review
                                        </option>
                                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>
                                            Publish Immediately</option>
                                    </select>
                                    @error('status')
                                        <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Payment Status <span
                                            class="text-red-500">*</span></label>
                                    <select name="payment_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                                        <option value="free"
                                            {{ old('payment_status', 'free') === 'free' ? 'selected' : '' }}>Free Post
                                        </option>
                                        <option value="pending"
                                            {{ old('payment_status') === 'pending' ? 'selected' : '' }}>Payment Pending
                                        </option>
                                        <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>
                                            Paid Post</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="block mt-1 text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 space-y-6">
                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/60">
                                    <h3 class="mb-4 flex items-center text-sm font-semibold text-gray-900 dark:text-white">
                                        <x-heroicon-o-eye class="mr-2 h-5 w-5 text-blue-600" />
                                        Summary
                                    </h3>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Title</span>
                                            <p class="text-sm font-semibold text-gray-900" data-summary="title">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Age</span>
                                            <p class="text-sm text-gray-900" data-summary="age">—</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Description</span>
                                            <p class="text-sm text-gray-700" data-summary="description">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Location</span>
                                            <p class="text-sm text-gray-900" data-summary="location">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Category</span>
                                            <p class="text-sm text-gray-900" data-summary="category">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Email</span>
                                            <p class="text-sm text-gray-900" data-summary="email">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-gray-500">Phone</span>
                                            <p class="text-sm text-gray-900" data-summary="phone">—</p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Files
                                                Selected</span>
                                            <p class="text-sm text-gray-900" data-summary="files-count">0 files</p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Post
                                                Status</span>
                                            <p class="text-sm text-gray-900" data-summary="status">Pending Review</p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium uppercase tracking-wide text-gray-500">Payment
                                                Status</span>
                                            <p class="text-sm text-gray-900" data-summary="payment-status">Free Post</p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <ul id="summaryFileList" class="space-y-2 text-sm text-gray-700"></ul>
                                    </div>
                                </div>

                                <div class="rounded-lg border border-green-200 bg-green-50 p-6">
                                    <p class="text-sm text-green-800">
                                        Everything looks good! Submit your post and our moderators will review it shortly.
                                    </p>
                                </div>

                                <div class="text-center">
                                    <button type="submit"
                                        class="px-8 py-3 font-medium text-white transition duration-200 bg-blue-600 rounded-md hover:bg-blue-700">
                                        Submit Post
                                    </button>
                                    <p class="mt-3 text-xs text-gray-500">By submitting, you agree to our content
                                        guidelines
                                        and community policies.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between px-8 py-6 border-t bg-gray-50" id="navigationControls">
                        <button type="button" id="prevBtn"
                            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition duration-200">
                            Previous
                        </button>
                        <button type="button" id="nextBtn"
                            class="px-6 py-2 font-medium text-white transition duration-200 bg-blue-600 rounded-md hover:bg-blue-700">
                            Next
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentStep = {{ $currentStep }};
        const totalSteps = 5;

        const allStates = @json($countries->pluck('states')->flatten());
        const allCities = @json($countries->pluck('states')->flatten()->pluck('cities')->flatten());

        const allCategories = [];
        @foreach ($sections as $section)
            @foreach ($section->categories as $category)
                allCategories.push({
                    id: {{ $category->id }},
                    name: "{{ $category->name }}",
                    section_id: {{ $section->id }}
                });
            @endforeach
        @endforeach

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('postForm');
            const stepSections = document.querySelectorAll('[data-step]');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.querySelector('[data-progress-text]');
            const stepDisplay = document.querySelector('[data-step-display]');
            const stepCircles = document.querySelectorAll('[data-step-circle]');
            const stepLabelsEls = document.querySelectorAll('[data-step-label]');
            const navigationControls = document.getElementById('navigationControls');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const mediaInput = form.querySelector('input[name="media[]"]');
            const summaryFields = form.querySelectorAll('[data-summary]');
            const summaryFileList = document.getElementById('summaryFileList');

            const titleInput = form.querySelector('input[name="title"]');
            const descriptionInput = form.querySelector('textarea[name="description"]');
            const ageInput = form.querySelector('input[name="age"]');
            const emailInput = form.querySelector('input[name="email"]');
            const phoneInput = form.querySelector('input[name="phone"]');
            const countrySelect = document.getElementById('country_id');
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');
            const sectionSelect = document.getElementById('section_id');
            const categorySelect = document.getElementById('category_id');
            const statusSelect = form.querySelector('select[name="status"]');
            const paymentSelect = form.querySelector('select[name="payment_status"]');

            initializeDropdowns();

            if (countrySelect) {
                countrySelect.addEventListener('change', () => populateStates(countrySelect.value));
            }

            if (stateSelect) {
                stateSelect.addEventListener('change', () => populateCities(stateSelect.value));
            }

            if (sectionSelect) {
                sectionSelect.addEventListener('change', () => populateCategories(parseInt(sectionSelect.value ||
                    0)));
            }

            nextBtn.addEventListener('click', () => {
                if (!validateStep(currentStep)) {
                    return;
                }
                showStep(currentStep + 1);
            });

            prevBtn.addEventListener('click', () => {
                showStep(currentStep - 1);
            });

            form.querySelectorAll('input, textarea, select').forEach((field) => {
                field.addEventListener('change', () => {
                    if (currentStep === 5) {
                        populateSummary();
                    }
                    updateProgress();
                });
            });

            showStep(currentStep);

            function showStep(step) {
                currentStep = Math.max(1, Math.min(step, totalSteps));

                stepSections.forEach((section) => {
                    section.classList.toggle('hidden', Number(section.dataset.step) !== currentStep);
                });

                prevBtn.classList.toggle('invisible', currentStep === 1);
                navigationControls.classList.toggle('hidden', currentStep === totalSteps);

                nextBtn.textContent = currentStep === totalSteps - 1 ? 'Review' : 'Next';
                stepDisplay.textContent = Math.min(currentStep, 4);

                updateProgress();
                updateStepIndicators();

                if (currentStep === totalSteps) {
                    populateSummary();
                }
            }

            function updateStepIndicators() {
                stepCircles.forEach((circle) => {
                    const step = Number(circle.dataset.stepCircle);
                    const indicatorCompleted = currentStep > step;
                    const indicatorCurrent = currentStep === step;

                    circle.classList.remove('bg-green-600', 'text-white', 'bg-blue-600', 'bg-gray-200',
                        'text-gray-600');

                    if (indicatorCompleted) {
                        circle.classList.add('bg-green-600', 'text-white');
                    } else if (indicatorCurrent) {
                        circle.classList.add('bg-blue-600', 'text-white');
                    } else {
                        circle.classList.add('bg-gray-200', 'text-gray-600');
                    }
                });

                stepLabelsEls.forEach((label) => {
                    const step = Number(label.dataset.stepLabel);
                    const indicatorCompleted = currentStep > step;
                    const indicatorCurrent = currentStep === step;

                    label.classList.remove('text-blue-600', 'text-gray-500');
                    label.classList.add(indicatorCompleted || indicatorCurrent ? 'text-blue-600' :
                        'text-gray-500');
                });

                const reviewLabel = document.querySelector('[data-step-indicator="5"] span');
                if (reviewLabel) {
                    reviewLabel.classList.remove('text-green-600', 'text-gray-500');
                    reviewLabel.classList.add(currentStep === 5 ? 'text-green-600' : 'text-gray-500');
                }
            }

            function updateProgress() {
                const hasBasic = Boolean(titleInput?.value.trim() && descriptionInput?.value.trim() && ageInput
                    ?.value);
                const hasLocation = Boolean(countrySelect?.value && stateSelect?.value && citySelect?.value);
                const hasContact = Boolean(emailInput?.value.trim() && phoneInput?.value.trim());
                const hasCategory = Boolean(sectionSelect?.value && categorySelect?.value && mediaInput?.files
                    .length);

                const completed = [hasBasic, hasLocation, hasContact, hasCategory].filter(Boolean).length;
                const percent = currentStep === totalSteps ? 100 : Math.round((completed / 4) * 100);

                progressBar.style.width = `${percent}%`;
                progressText.textContent = `${percent}%`;
            }

            function populateSummary() {
                const getSelectedText = (select) => {
                    if (!select || !select.options.length || select.selectedIndex === -1) {
                        return '';
                    }
                    return select.options[select.selectedIndex].text.trim();
                };

                const locationText = [getSelectedText(citySelect), getSelectedText(stateSelect), getSelectedText(
                        countrySelect)]
                    .filter(Boolean)
                    .join(', ') || 'Not selected';

                const categoryText = [getSelectedText(sectionSelect), getSelectedText(categorySelect)]
                    .filter(Boolean)
                    .join(' · ') || 'Not selected';

                const statusText = getSelectedText(statusSelect) || 'Pending Review';
                const paymentText = getSelectedText(paymentSelect) || 'Free Post';

                const summaryValues = {
                    title: titleInput?.value.trim() || 'No title entered yet',
                    description: descriptionInput?.value.trim() ||
                        'Add a detailed description so buyers know what to expect.',
                    age: ageInput?.value || '—',
                    location: locationText,
                    category: categoryText,
                    email: emailInput?.value.trim() || 'Not provided',
                    phone: phoneInput?.value.trim() || 'Not provided',
                    'files-count': `${mediaInput?.files.length || 0} file${mediaInput?.files.length === 1 ? '' : 's'} selected`,
                    status: statusText,
                    'payment-status': paymentText,
                };

                summaryFields.forEach((field) => {
                    const key = field.dataset.summary;
                    if (!summaryValues.hasOwnProperty(key)) {
                        return;
                    }
                    field.textContent = summaryValues[key];
                });

                summaryFileList.innerHTML = '';
                if (mediaInput && mediaInput.files.length) {
                    Array.from(mediaInput.files).forEach((file) => {
                        const item = document.createElement('li');
                        const sizeInMb = (file.size / (1024 * 1024)).toFixed(1);
                        item.className =
                            'flex items-center justify-between rounded border border-gray-200 bg-white px-3 py-2 text-xs text-gray-600';
                        item.innerHTML =
                            `<span class="truncate">${file.name}</span><span class="ml-4 whitespace-nowrap">${sizeInMb} MB</span>`;
                        summaryFileList.appendChild(item);
                    });
                } else {
                    const emptyState = document.createElement('li');
                    emptyState.className = 'text-xs text-gray-500';
                    emptyState.textContent = 'No media selected yet.';
                    summaryFileList.appendChild(emptyState);
                }
            }

            function validateStep(step) {
                if (step === 1) {
                    if (!titleInput?.value.trim() || !descriptionInput?.value.trim() || !ageInput?.value) {
                        alert('Please complete the title, description, and age fields before continuing.');
                        return false;
                    }
                } else if (step === 2) {
                    if (!countrySelect?.value || !stateSelect?.value || !citySelect?.value) {
                        alert('Please select a country, state, and city before proceeding.');
                        return false;
                    }
                } else if (step === 3) {
                    if (!emailInput?.value.trim() || !phoneInput?.value.trim()) {
                        alert('Please provide both your email address and phone number.');
                        return false;
                    }
                } else if (step === 4) {
                    if (!sectionSelect?.value || !categorySelect?.value) {
                        alert('Please choose a section and category before proceeding.');
                        return false;
                    }

                    if (!mediaInput || mediaInput.files.length === 0) {
                        alert('Please upload at least one photo or video.');
                        return false;
                    }

                    if (mediaInput.files.length > 10) {
                        alert('You can upload up to 10 files only.');
                        return false;
                    }

                    const oversized = Array.from(mediaInput.files).find((file) => file.size > 10 * 1024 * 1024);
                    if (oversized) {
                        alert(`${oversized.name} exceeds the 10MB limit. Please choose a smaller file.`);
                        return false;
                    }
                }

                return true;
            }

            function populateStates(countryId) {
                if (!stateSelect) {
                    return;
                }

                stateSelect.innerHTML = '<option value="">Select State</option>';
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = true;

                if (!countryId) {
                    stateSelect.disabled = true;
                    return;
                }

                const states = allStates.filter((state) => String(state.country_id) === String(countryId));
                states.forEach((state) => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });

                stateSelect.disabled = false;
            }

            function populateCities(stateId) {
                if (!citySelect) {
                    return;
                }

                citySelect.innerHTML = '<option value="">Select City</option>';

                if (!stateId) {
                    citySelect.disabled = true;
                    return;
                }

                const cities = allCities.filter((city) => String(city.state_id) === String(stateId));
                cities.forEach((city) => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });

                citySelect.disabled = false;
            }

            function populateCategories(sectionId) {
                if (!categorySelect) {
                    return;
                }

                categorySelect.innerHTML = '<option value="">Select Category</option>';

                if (!sectionId) {
                    categorySelect.disabled = true;
                    return;
                }

                const categories = allCategories.filter((category) => category.section_id === sectionId);
                categories.forEach((category) => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });

                categorySelect.disabled = false;
            }

            function initializeDropdowns() {
                if (countrySelect && countrySelect.value) {
                    populateStates(countrySelect.value);
                }

                if (stateSelect && stateSelect.value) {
                    populateCities(stateSelect.value);
                }

                if (sectionSelect && sectionSelect.value) {
                    populateCategories(parseInt(sectionSelect.value));
                }
            }
        });
    </script>
@endsection
