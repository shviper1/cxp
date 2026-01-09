@extends('layouts.app')

@php
    $pageTitle = "{$section->name} in {$country->name} - Categories";
    $pageDescription = "Browse {$section->name} categories available in {$country->name}";
    $pageKeywords = 'listings, categories, ' . strtolower($section->name) . ', ' . strtolower($country->name);
@endphp

@section('content')
    <section class="py-12 bg-slate-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-slate-600">
                    <li><a href="{{ url('/') }}" class="hover:text-slate-900">Home</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li><a href="{{ route('locations.country', $country) }}"
                            class="hover:text-slate-900">{{ $country->name }}</a></li>
                    <li><span class="text-slate-400">/</span></li>
                    <li class="font-medium text-slate-900">{{ $section->name }}</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-12 text-center">
                <h1 class="mb-4 text-4xl font-bold text-slate-900">
                    {{ $section->name }} in {{ $country->name }}
                </h1>
                <p class="max-w-2xl mx-auto text-xl text-slate-600">
                    Choose a category to find {{ strtolower($section->name) }} listings
                </p>
            </div>

            <!-- Categories Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($categories as $category)
                    <div class="transition-shadow bg-white border shadow-sm rounded-xl border-slate-200 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $category->name }}</h3>
                                    <p class="text-sm text-slate-500">{{ $category->posts_count }} listings</p>
                                </div>
                                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                                    <span class="text-lg">{{ substr($category->name, 0, 1) }}</span>
                                </div>
                            </div>

                            <p class="mb-6 text-sm text-slate-600 line-clamp-2">
                                {{ $category->description ?? 'Browse ' . strtolower($category->name) . ' listings in ' . $country->name }}
                            </p>

                            <a href="{{ route('locations.category.posts', [$country, $section, $category]) }}"
                                class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                View {{ $category->posts_count }} {{ Str::plural('listing', $category->posts_count) }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center col-span-full">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-slate-900">No categories available</h3>
                        <p class="text-slate-500">There are no active listings in {{ $section->name }} section yet.</p>
                        @auth
                            <a href="{{ route('posts.create') }}"
                                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                Create the first listing
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                Login to create listings
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- Back navigation -->
            <div class="flex justify-center mt-12 space-x-4">
                <a href="{{ route('locations.country', $country) }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium transition-colors rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                    ← Back to sections
                </a>
                <a href="{{ url('/') }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-medium transition-colors rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                    Browse all countries
                </a>
            </div>
        </div>
    </section>
@endsection
