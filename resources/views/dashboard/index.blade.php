@extends('layouts.app')

@php
    $pageTitle = 'My Dashboard';
    $pageDescription = 'Manage your posts and account settings.';
@endphp

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600">Manage your posts and account settings from your dashboard.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-500 rounded-full p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Posts</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $posts->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-yellow-500 rounded-full p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $posts->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-500 rounded-full p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $posts->where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-red-500 rounded-full p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $posts->where('status', 'rejected')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('posts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Post
            </a>
            <a href="{{ route('dashboard.posts') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                View All Posts
            </a>
            <a href="{{ route('dashboard.profile') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Edit Profile
            </a>

            @if(!Auth::user()->isVerified())
                <a href="{{ route('dashboard.verification') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Verify Account
                </a>
            @endif
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Recent Posts</h2>
            <a href="{{ route('dashboard.posts') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
        </div>

        @if($posts->count() > 0)
            <div class="space-y-4">
                @foreach($posts->take(5) as $post)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-1">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($post->description, 100) }}</p>
                            <div class="flex items-center text-xs text-gray-500 space-x-4">
                                <span>{{ $post->city->name }}, {{ $post->state->name }}</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            @if($post->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Pending Review</span>
                            @elseif($post->status === 'approved')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Approved</span>
                            @elseif($post->status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Rejected</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-500 mb-4">Create your first post to get started!</p>
                <a href="{{ route('posts.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                    Create Your First Post
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
