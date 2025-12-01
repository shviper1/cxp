@extends('layouts.app')

@php
    $pageTitle = $post->title;
    $pageDescription = Str::limit($post->description, 160);
    $pageKeywords = 'listing, ' . strtolower($post->category->name) . ', ' . strtolower($post->city->name);
@endphp

@section('content')
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-slate-600">
                <li><a href="{{ url('/') }}" class="hover:text-slate-900">Home</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li><a href="{{ route('locations.country', $post->country) }}" class="hover:text-slate-900">{{ $post->country->name }}</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li><a href="{{ route('locations.section', [$post->country, $post->section]) }}" class="hover:text-slate-900">{{ $post->section->name }}</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li><a href="{{ route('locations.category.posts', [$post->country, $post->section, $post->category]) }}" class="hover:text-slate-900">{{ $post->category->name }}</a></li>
                <li><span class="text-slate-400">/</span></li>
                <li class="font-medium text-slate-900">{{ Str::limit($post->title, 30) }}</li>
            </ol>
        </nav>

        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Images -->
                @if($post->media->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="aspect-video bg-slate-100 relative">
                            <img src="{{ Storage::url($post->media->first()->path) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @if($post->media->count() > 1)
                            <div class="p-4 grid grid-cols-4 gap-2">
                                @foreach($post->media->skip(1)->take(3) as $media)
                                    <img src="{{ Storage::url($media->path) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full aspect-square object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity">
                                @endforeach
                                @if($post->media->count() > 4)
                                    <div class="aspect-square bg-slate-100 rounded-lg flex items-center justify-center">
                                        <span class="text-sm text-slate-500">+{{ $post->media->count() - 4 }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Post Details -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $post->title }}</h1>
                            <div class="flex items-center space-x-4 text-sm text-slate-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $post->user->name ?? 'Anonymous' }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $post->city->name }}, {{ $post->state->name }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($post->status === 'approved') bg-green-100 text-green-800
                                @elseif($post->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($post->status) }}
                            </span>
                            @if($post->payment_status === 'paid')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Paid
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none">
                        <h3 class="text-lg font-semibold text-slate-900 mb-3">Description</h3>
                        <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $post->description }}
                        </div>
                    </div>

                    <!-- Tags/Categories -->
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                {{ $post->section->name }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $post->category->name }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ $post->country->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Contact Information</h3>
                    <div class="space-y-3">
                        @if($post->email)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-slate-700">{{ $post->email }}</span>
                            </div>
                        @endif
                        @if($post->phone)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-slate-700">{{ $post->phone }}</span>
                            </div>
                        @endif
                        @if($post->age)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-slate-700">Age: {{ $post->age }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Create Post Button -->
                @auth
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Create Your Own Listing</h3>
                        <p class="text-blue-700 mb-4 text-sm">
                            Have something to sell or offer services? Create your own listing now.
                        </p>
                        <a href="{{ route('posts.create') }}"
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Listing
                        </a>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-yellow-900 mb-3">Want to Create Listings?</h3>
                        <p class="text-yellow-700 mb-4 text-sm">
                            Join our community and start creating your own listings today.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('login') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                Login to Create Listings
                            </a>
                            <a href="{{ route('register') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                                Create Account
                            </a>
                        </div>
                    </div>
                @endauth

                <!-- Similar Listings -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">More in {{ $post->category->name }}</h3>
                    <a href="{{ route('locations.category.posts', [$post->country, $post->section, $post->category]) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                        View All {{ $post->category->name }} Listings
                    </a>
                </div>
            </div>
        </div>

        <!-- Back navigation -->
        <div class="mt-12 flex justify-center">
            <a href="{{ route('locations.category.posts', [$post->country, $post->section, $post->category]) }}"
               class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                ← Back to {{ $post->category->name }} listings
            </a>
        </div>
    </div>
</section>
@endsection
