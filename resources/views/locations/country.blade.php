@extends('layouts.app')

@php
    $pageTitle = "Browse {$country->name} - Sections & Categories";
    $pageDescription = "Explore sections and categories available in {$country->name}";
    $pageKeywords = 'listings, categories, sections, ' . strtolower($country->name);
@endphp

@section('content')
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-slate-600">
                <li><a href="{{ url('/') }}" class="hover:text-slate-900">Home</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li class="font-medium text-slate-900">{{ $country->name }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-bold text-slate-900 mb-4">
                Browse {{ $country->name }}
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                Choose a section to find listings in {{ $country->name }}
            </p>
        </div>

        <!-- Sections Grid -->
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($sections as $section)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <span class="text-2xl">{{ substr($section->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $section->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $section->categories->count() }} categories</p>
                            </div>
                        </div>

                        @if($section->categories->isNotEmpty())
                            <div class="space-y-2 mb-4">
                                @foreach($section->categories->take(3) as $category)
                                    <a href="{{ route('locations.section', [$country, $section]) }}"
                                       class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-md transition-colors">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                                @if($section->categories->count() > 3)
                                    <p class="text-xs text-slate-500 px-3">
                                        +{{ $section->categories->count() - 3 }} more categories
                                    </p>
                                @endif
                            </div>

                            <a href="{{ route('locations.section', [$country, $section]) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Browse {{ $section->name }}
                            </a>
                        @else
                            <p class="text-sm text-slate-500 mb-4">No categories available in this section yet.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">No sections available</h3>
                    <p class="text-slate-500">There are no active listings in {{ $country->name }} yet.</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Be the first to post here
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Login to create posts
                        </a>
                    @endauth
                </div>
            @endforelse
        </div>

        <!-- Back to home -->
        <div class="mt-12 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                ← Back to all countries
            </a>
        </div>
    </div>
</section>
@endsection
