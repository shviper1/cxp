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
    <div
        class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-indigo-100 py-10 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            <div
                class="relative overflow-hidden rounded-3xl border border-white/80 bg-white/95 shadow-xl backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/85">
                <div
                    class="pointer-events-none absolute inset-x-0 top-0 h-28 bg-gradient-to-r from-blue-400/15 via-transparent to-emerald-400/20 dark:from-blue-500/10 dark:via-transparent dark:to-emerald-500/10">
                </div>

                <!-- Header -->
                <div class="relative px-6 py-8 sm:px-10">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="max-w-xl space-y-2">
                            <div
                                class="inline-flex items-center gap-2 rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-600 shadow-sm dark:bg-blue-500/15 dark:text-blue-300">
                                Ready to publish
                            </div>
                            <div>
                                <h1 class="text-2xl font-semibold text-slate-900 sm:text-3xl dark:text-white">Create New
                                    Post</h1>
                                <p class="mt-1 text-sm text-slate-500 sm:text-base dark:text-slate-400">Share your story with
                                    a few guided steps. We keep everything lightweight and mobile friendly.</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-3 self-start rounded-2xl bg-white/80 px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-white/50 dark:bg-slate-900/60 dark:text-slate-200 dark:ring-slate-800">
                            <span>Step</span>
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-500 text-white shadow-sm">
                                <span data-step-display>{{ $displayStep }}</span>
                            </span>
                            <span>of 4</span>
                        </div>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div
                        class="mx-6 mt-4 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm font-medium text-emerald-700 shadow-sm sm:mx-10 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-200">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Progress Bar -->
                <div
                    class="relative border-t border-b border-white/80 bg-white/70 px-6 py-6 sm:px-10 dark:border-slate-800/60 dark:bg-slate-900/60">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="space-y-1">
                            <span
                                class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Progress</span>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Complete the essentials to move forward —
                                you can always go back.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300"
                                data-progress-text>{{ $progressPercent }}%</span>
                            <div class="h-2 w-36 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                                <div id="progressBar"
                                    class="h-full rounded-full bg-gradient-to-r from-blue-500 via-sky-500 to-emerald-500 transition-all duration-500"
                                    style="width: {{ $progressPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 -mx-2 overflow-x-auto pb-1">
                        <div
                            class="flex min-w-[560px] items-start justify-between gap-4 px-2 md:min-w-full md:flex-wrap md:justify-start">
                            @foreach ($stepLabels as $index => $step)
                                @php
                                    $isCompleted = $currentStep > $index;
                                    $isCurrent = $currentStep === $index;
                                    $circleClasses = $isCompleted
                                        ? 'bg-emerald-500 text-white shadow-sm'
                                        : ($isCurrent
                                            ? 'bg-blue-500 text-white shadow-sm'
                                            : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300');
                                    $labelClasses =
                                        $isCompleted || $isCurrent
                                            ? 'text-blue-600 dark:text-blue-300'
                                            : 'text-slate-500 dark:text-slate-400';
                                @endphp
                                <div class="flex min-w-[130px] flex-col items-center justify-center rounded-2xl border border-transparent px-3 py-2 text-center transition md:items-start md:text-left"
                                    data-step-indicator="{{ $index }}">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition-all {{ $circleClasses }}"
                                        data-step-circle="{{ $index }}">
                                        {{ $index }}
                                    </div>
                                    <span class="mt-2 text-[11px] font-semibold uppercase tracking-wide {{ $labelClasses }}"
                                        data-step-label="{{ $index }}">
                                        {{ $step['title'] }}
                                    </span>
                                    <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400 md:max-w-[220px]">
                                        {{ $step['subtitle'] }}</p>
                                </div>
                            @endforeach
                            <div class="flex min-w-[130px] flex-col items-center justify-center rounded-2xl border border-transparent px-3 py-2 text-center transition md:items-start md:text-left"
                                data-step-indicator="5">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition-all {{ $currentStep === 5 ? 'bg-emerald-500 text-white shadow-sm' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}"
                                    data-step-circle="5">
                                    ✓
                                </div>
                                <span
                                    class="mt-2 text-[11px] font-semibold uppercase tracking-wide {{ $currentStep === 5 ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
                                    Review
                                </span>
                                <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400 md:max-w-[220px]">Preview
                                    &amp; submit</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                    @csrf

                    <!-- Step Content -->
                    <div class="space-y-10 px-6 py-8 sm:px-10">
                        <!-- Step 1: Basic Information -->
                        <div class="step-content mx-auto max-w-3xl rounded-2xl border border-white/80 bg-white/95 p-6 shadow-sm ring-1 ring-white/70 transition dark:border-slate-800/60 dark:bg-slate-900/75 {{ $currentStep === 1 ? '' : 'hidden' }}"
                            data-step="1">
                            <div class="mb-6 space-y-2 text-center sm:text-left">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Basic Information</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Share the essentials so readers
                                    instantly understand what your post is about.</p>
                            </div>

                            <div class="grid gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Post Title
                                        <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" value="{{ $basicTitle }}"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
                                        placeholder="Give your post a memorable name">
                                    @error('title')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Description
                                        <span class="text-rose-500">*</span></label>
                                    <textarea name="description" rows="5"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
                                        placeholder="Describe what makes this post helpful or exciting">{{ $basicDescription }}</textarea>
                                    @error('description')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Age
                                            <span class="text-rose-500">*</span></label>
                                        <input type="number" name="age" value="{{ $basicAge }}" min="1"
                                            class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
                                            placeholder="Your age">
                                        @error('age')
                                            <span class="block text-sm text-rose-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Location Details -->
                        <div class="step-content mx-auto max-w-3xl rounded-2xl border border-white/80 bg-white/95 p-6 shadow-sm ring-1 ring-white/70 transition dark:border-slate-800/60 dark:bg-slate-900/75 {{ $currentStep === 2 ? '' : 'hidden' }}"
                            data-step="2">
                            <div class="mb-6 space-y-2 text-center sm:text-left">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Location Details</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Pin the right spot so people know
                                    exactly where this post belongs.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Country
                                        <span class="text-rose-500">*</span></label>
                                    <select name="country_id" id="country_id"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ $chosenCountryId == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">State
                                        <span class="text-rose-500">*</span></label>
                                    <select name="state_id" id="state_id"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
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
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">City
                                        <span class="text-rose-500">*</span></label>
                                    <select name="city_id" id="city_id"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
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
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            @if ($selectedCity && $selectedCity->isPaid())
                                <div
                                    class="mt-6 rounded-2xl border border-amber-200 bg-amber-50/80 p-4 text-sm text-amber-700 shadow-sm dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-200">
                                    This location requires payment to post.
                                </div>
                            @endif
                        </div>

                        <!-- Step 3: Contact Information -->
                        <div class="step-content mx-auto max-w-3xl rounded-2xl border border-white/80 bg-white/95 p-6 shadow-sm ring-1 ring-white/70 transition dark:border-slate-800/60 dark:bg-slate-900/75 {{ $currentStep === 3 ? '' : 'hidden' }}"
                            data-step="3">
                            <div class="mb-6 space-y-2 text-center sm:text-left">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Contact Information</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Let us know how the team can reach
                                    you for quick approvals.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Email
                                        Address <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ $contactEmail }}"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
                                        placeholder="your.email@example.com">
                                    @error('email')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Phone
                                        Number <span class="text-rose-500">*</span></label>
                                    <input type="text" name="phone" value="{{ $contactPhone }}"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
                                        placeholder="+1 (555) 123-4567">
                                    @error('phone')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="mt-6 flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50/80 p-4 text-sm text-blue-700 shadow-sm dark:border-blue-400/30 dark:bg-blue-400/10 dark:text-blue-200">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                <p>Your contact details stay private and are only visible to moderators during review.</p>
                            </div>
                        </div>

                        <!-- Step 4: Category & Media -->
                        <div class="step-content mx-auto max-w-3xl space-y-8 rounded-2xl border border-white/80 bg-white/95 p-6 shadow-sm ring-1 ring-white/70 transition dark:border-slate-800/60 dark:bg-slate-900/75 {{ $currentStep === 4 ? '' : 'hidden' }}"
                            data-step="4">
                            <div class="space-y-2 text-center sm:text-left">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Category &amp; Media</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Find the best fit and add visuals
                                    that boost trust.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Section
                                        <span class="text-rose-500">*</span></label>
                                    <select name="section_id" id="section_id"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20">
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ $chosenSectionId == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Category
                                        <span class="text-rose-500">*</span></label>
                                    <select name="category_id" id="category_id"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
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
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Photos
                                        &amp; Videos <span class="text-rose-500">*</span></label>
                                    <input type="file" name="media[]" multiple
                                        class="w-full rounded-xl border border-dashed border-slate-300 bg-white px-4 py-6 text-sm text-slate-500 shadow-sm outline-none transition hover:border-blue-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-blue-400 dark:focus:border-blue-500 dark:focus:ring-blue-400/20"
                                        accept="image/*,video/*">
                                    @error('media.*')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-xs text-slate-500 shadow-sm dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-300">
                                    Upload up to 10 files (images or videos, 10MB max each). Existing uploads stay attached
                                    unless you replace them.
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Review & Submit -->
                        <div class="step-content mx-auto max-w-4xl space-y-8 rounded-2xl border border-white/80 bg-white/95 p-6 shadow-sm ring-1 ring-white/70 transition dark:border-slate-800/60 dark:bg-slate-900/75 {{ $currentStep === 5 ? '' : 'hidden' }}"
                            data-step="5">
                            <div class="space-y-2 text-center sm:text-left">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Review &amp; Submit</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Give everything a final glance before
                                    you send it to the team.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Post
                                        Status <span class="text-rose-500">*</span></label>
                                    <select name="status"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20">
                                        <option value="pending"
                                            {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending Review
                                        </option>
                                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>
                                            Publish Immediately
                                        </option>
                                    </select>
                                    @error('status')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Payment
                                        Status <span class="text-rose-500">*</span></label>
                                    <select name="payment_status"
                                        class="w-full rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:focus:border-blue-500 dark:focus:ring-blue-400/20">
                                        <option value="free"
                                            {{ old('payment_status', 'free') === 'free' ? 'selected' : '' }}>Free Post
                                        </option>
                                        <option value="pending"
                                            {{ old('payment_status') === 'pending' ? 'selected' : '' }}>Payment Pending
                                        </option>
                                        <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>
                                            Paid Post
                                        </option>
                                    </select>
                                    @error('payment_status')
                                        <span class="block text-sm text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div
                                    class="rounded-2xl border border-slate-200 bg-slate-50/80 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/60">
                                    <h3
                                        class="mb-4 flex items-center text-sm font-semibold text-slate-900 dark:text-white">
                                        <x-heroicon-o-eye class="mr-2 h-5 w-5 text-blue-500" />
                                        Summary
                                    </h3>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Title</span>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100"
                                                data-summary="title">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Age</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100" data-summary="age">—</p>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Description</span>
                                            <p class="text-sm text-slate-700 dark:text-slate-300"
                                                data-summary="description">—</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Location</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100" data-summary="location">
                                                —</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Category</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100" data-summary="category">
                                                —</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Email</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100" data-summary="email">—
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Phone</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100" data-summary="phone">—
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium uppercase tracking-wide text-slate-500">Files
                                                Selected</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100"
                                                data-summary="files-count">0 files</p>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium uppercase tracking-wide text-slate-500">Post
                                                Status</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100" data-summary="status">
                                                Pending Review</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs font-medium uppercase tracking-wide text-slate-500">Payment
                                                Status</span>
                                            <p class="text-sm text-slate-900 dark:text-slate-100"
                                                data-summary="payment-status">Free Post</p>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <ul id="summaryFileList"
                                            class="space-y-2 text-sm text-slate-600 dark:text-slate-300"></ul>
                                    </div>
                                </div>

                                <div
                                    class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-6 text-sm text-emerald-700 shadow-sm dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-200">
                                    Everything looks great! Submit now and our moderators will take a quick look.
                                </div>

                                <div
                                    class="flex flex-col items-center gap-3 text-center sm:flex-row sm:justify-between sm:text-left">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 sm:max-w-sm">By submitting, you
                                        agree to our content guidelines and community policies.</p>
                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 px-8 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900 sm:w-auto">
                                        Submit Post
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex flex-col gap-3 border-t border-white/80 bg-white/80 px-6 py-6 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/70 sm:flex-row sm:items-center sm:justify-between"
                        id="navigationControls">
                        <button type="button" id="prevBtn"
                            class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-slate-300 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-200/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:text-white dark:focus:ring-blue-400/30 sm:w-auto">
                            Previous
                        </button>
                        <button type="button" id="nextBtn"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900 sm:w-auto">
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
