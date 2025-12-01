@extends('layouts.app')

@php
    $pageTitle = "{$category->name} - {$section->name} in {$country->name}";
    $pageDescription = "Browse {$category->name} listings in {$section->name} section, {$country->name}";
    $pageKeywords = 'listings, ' . strtolower($category->name) . ', ' . strtolower($section->name) . ', ' . strtolower($country->name);
@endphp

@section('content')
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-slate-600">
                <li><a href="{{ url('/') }}" class="hover:text-slate-900">Home</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li><a href="{{ route('locations.country', $country) }}" class="hover:text-slate-900">{{ $country->name }}</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li><a href="{{ route('locations.section', [$country, $section]) }}" class="hover:text-slate-900">{{ $section->name }}</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li class="font-medium text-slate-900">{{ $category->name }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 mb-2">
                        {{ $category->name }}
                    </h1>
                    <p class="text-xl text-slate-600">
                        {{ $section->name }} • {{ $country->name }}
                    </p>
                    <p class="text-slate-500 mt-2">
                        {{ $posts->total() }} {{ Str::plural('listing', $posts->total()) }} found
                    </p>
                </div>

                @auth
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Listing
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login to Create Listing
                    </a>
                @endauth
            </div>
        </div>

        <!-- Posts Grid -->
        @if($posts->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 mb-12">
                @foreach($posts as $post)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="relative">
                            @if($post->media->isNotEmpty())
                                <img src="{{ Storage::url($post->media->first()->path) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-48 object-cover rounded-t-xl">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-slate-200 to-slate-300 rounded-t-xl flex items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($post->status === 'approved') bg-green-100 text-green-800
                                    @elseif($post->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-2 line-clamp-2">
                                <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p class="text-sm text-slate-600 mb-3 line-clamp-2">
                                {{ $post->description }}
                            </p>

                            <div class="flex items-center justify-between text-sm text-slate-500 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $post->user->name ?? 'Anonymous' }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="text-lg font-bold text-slate-900">
                                    {{ $post->city->name }}, {{ $post->state->name }}
                                </div>
                                <a href="{{ route('posts.show', $post) }}"
                                   class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 text-sm font-medium rounded-md hover:bg-slate-200 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">No listings found</h3>
                <p class="text-slate-600 mb-8 max-w-md mx-auto">
                    There are no active {{ strtolower($category->name) }} listings in {{ $section->name }} section yet.
                </p>
                @auth
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create the first listing
                    </a>
                @else
                    <div class="space-y-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login to create listings
                        </a>
                        <p class="text-sm text-slate-500">or</p>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Create your account
                        </a>
                    </div>
                @endauth
            </div>
        @endif

        <!-- Back navigation -->
        <div class="mt-12 flex justify-center space-x-4">
            <a href="{{ route('locations.section', [$country, $section]) }}" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                ← Back to categories
            </a>
            <a href="{{ route('locations.country', $country) }}" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                Browse all sections
            </a>
        </div>
    </div>
</section>
@endsection
