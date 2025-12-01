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
                    @if((request('country_id') || $selectedCountry) && (request('state_id') || ($selectedCity && $selectedCity->state)) && (request('city_id') || $selectedCity) && request('category_id') && request('title') && request('description') && request('age') && request('email') && request('phone'))
                        100%
                    @elseif(request('category_id'))
                        75%
                    @elseif((request('city_id') || $selectedCity))
                        50%
                    @elseif((request('country_id') || $selectedCountry))
                        25%
                    @else
                        0%
                    @endif
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                     style="width:
                     @if((request('country_id') || $selectedCountry) && (request('state_id') || ($selectedCity && $selectedCity->state)) && (request('city_id') || $selectedCity) && request('category_id') && request('title') && request('description') && request('age') && request('email') && request('phone')) 100%
                     @elseif(request('category_id')) 75%
                     @elseif((request('city_id') || $selectedCity)) 50%
                     @elseif((request('country_id') || $selectedCountry)) 25%
                     @else 0%
                     @endif">
                </div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500">
                <span>Location</span>
                <span>Category</span>
                <span>Details & Media</span>
                <span>Complete</span>
            </div>
        </div>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Step 1: Location Section -->
            <div class="border-b pb-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3">1</span>
                    Location
                </h3>
                <p class="text-sm text-gray-600 mb-4">📍 Select the country, state, and city where your post should appear.</p>
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

            <!-- Step 2: Category Section (Show if city is selected) -->
            @if(request('city_id') || $selectedCity)
            <div class="border-b pb-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3">2</span>
                    Category
                </h3>
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
            @endif

            <!-- Step 3: Post Details & Media Section (Show if category is selected) -->
            @if(request('category_id'))
            <div class="border-b pb-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold mr-3">3</span>
                    Post Details & Media
                </h3>
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
                            <input type="email" name="email" value="{{ old('email', request('email', auth()->user()->email ?? '')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="your.email@example.com">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            @endif

            <!-- Payment Info (if applicable) -->
            @if($selectedCity && $selectedCity->isPaid())
                @php
                    $country = $selectedCity->state->country;
                    $amount = $country->amount ?? 0;
                    $currency = $country->currency_symbol ?? '$';
                @endphp
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">💰 Paid Post</h3>
                    <p class="text-yellow-700">This location requires paid posts. Amount: <strong>{{ $currency }} {{ number_format($amount, 2) }}</strong></p>
                    <p class="text-sm text-yellow-600 mt-2">Payment will be processed after submission.</p>
                </div>
            @endif

            <!-- Submit Button (Show if all required fields are filled) -->
            @if(request('category_id') && request('title') && request('description') && request('age') && request('email') && request('phone'))
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                    🚀 Submit Post
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Country change handler - reload page with selected country
    document.getElementById('country_id').addEventListener('change', function() {
        const countryId = this.value;
        if (countryId) {
            const url = new URL(window.location);
            url.searchParams.set('country_id', countryId);
            url.searchParams.delete('state_id');
            url.searchParams.delete('city_id');
            url.searchParams.delete('section_id');
            url.searchParams.delete('category_id');
            window.location.href = url.toString();
        }
    });

    // State change handler - reload page with selected state
    document.getElementById('state_id').addEventListener('change', function() {
        const stateId = this.value;
        const countryId = document.getElementById('country_id').value;
        if (stateId && countryId) {
            const url = new URL(window.location);
            url.searchParams.set('country_id', countryId);
            url.searchParams.set('state_id', stateId);
            url.searchParams.delete('city_id');
            url.searchParams.delete('section_id');
            url.searchParams.delete('category_id');
            window.location.href = url.toString();
        }
    });

    // City change handler - reload page with selected city
    document.getElementById('city_id').addEventListener('change', function() {
        const cityId = this.value;
        const countryId = document.getElementById('country_id').value;
        const stateId = document.getElementById('state_id').value;
        if (cityId && stateId && countryId) {
            const url = new URL(window.location);
            url.searchParams.set('country_id', countryId);
            url.searchParams.set('state_id', stateId);
            url.searchParams.set('city_id', cityId);
            url.searchParams.delete('section_id');
            url.searchParams.delete('category_id');
            window.location.href = url.toString();
        }
    });

    // Section change handler - reload page with selected section
    document.getElementById('section_id').addEventListener('change', function() {
        const sectionId = this.value;
        const countryId = document.getElementById('country_id').value;
        const stateId = document.getElementById('state_id').value;
        const cityId = document.getElementById('city_id').value;
        if (sectionId && cityId && stateId && countryId) {
            const url = new URL(window.location);
            url.searchParams.set('country_id', countryId);
            url.searchParams.set('state_id', stateId);
            url.searchParams.set('city_id', cityId);
            url.searchParams.set('section_id', sectionId);
            url.searchParams.delete('category_id');
            window.location.href = url.toString();
        }
    });

    // Category change handler - just update URL params
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        if (categoryId) {
            const url = new URL(window.location);
            url.searchParams.set('category_id', categoryId);
            // Update URL without reloading
            window.history.replaceState({}, '', url.toString());
        }
    });
});
</script>
@endsection
