@extends('layouts.app')

@php
    $pageTitle = 'My Posts';
    $pageDescription = 'View and manage all your submitted posts.';
@endphp

@section('content')
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">My Posts</h1>
                <p class="text-gray-600">View and manage all your submitted posts.</p>
            </div>
            <a href="{{ route('posts.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Create New Post
            </a>
        </div>

        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if ($posts->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Filters -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="flex space-x-4">
                            <a href="{{ route('dashboard.posts') }}"
                                class="px-3 py-1 rounded-full text-sm font-medium {{ !request('status') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                All ({{ $posts->total() }})
                            </a>
                            <a href="{{ route('dashboard.posts', ['status' => 'pending']) }}"
                                class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Pending ({{ $posts->where('status', 'pending')->count() }})
                            </a>
                            <a href="{{ route('dashboard.posts', ['status' => 'approved']) }}"
                                class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') === 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Approved ({{ $posts->where('status', 'approved')->count() }})
                            </a>
                            <a href="{{ route('dashboard.posts', ['status' => 'rejected']) }}"
                                class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                Rejected ({{ $posts->where('status', 'rejected')->count() }})
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Posts List -->
                <div class="divide-y divide-gray-200">
                    @foreach ($posts as $post)
                        <div class="p-6 hover:bg-gray-50 transition duration-200">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $post->title }}</h3>
                                        @if ($post->status === 'pending')
                                            <span
                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Pending
                                                Review</span>
                                        @elseif($post->status === 'approved')
                                            <span
                                                class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Approved</span>
                                        @elseif($post->status === 'rejected')
                                            <span
                                                class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Rejected</span>
                                        @endif
                                    </div>

                                    <p class="text-gray-600 mb-3">{{ Str::limit($post->description, 150) }}</p>

                                    <div class="flex items-center text-sm text-gray-500 space-x-4 mb-3">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $post->city->name }}, {{ $post->state->name }}, {{ $post->country->name }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $post->created_at->format('M j, Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                            {{ $post->category->name }}
                                        </span>
                                    </div>

                                    <!-- Media Preview -->
                                    @if ($post->media->count() > 0)
                                        <div class="flex space-x-2 mt-3">
                                            @foreach ($post->media->take(3) as $media)
                                                @if ($media->type === 'image')
                                                    <img src="{{ asset('storage/' . $media->file_path) }}" alt="Post media"
                                                        class="w-16 h-16 object-cover rounded-lg border">
                                                @endif
                                            @endforeach
                                            @if ($post->media->count() > 3)
                                                <div
                                                    class="w-16 h-16 bg-gray-200 rounded-lg border flex items-center justify-center text-xs text-gray-600">
                                                    +{{ $post->media->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-6 flex flex-col space-y-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
                                        onclick="showPostDetails({{ $post->id }})">
                                        View Details
                                    </button>
                                    @if ($post->status === 'approved')
                                        <a href="{{ route('posts.show', $post) }}" target="_blank" rel="noopener"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            View Public Post
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $posts->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No posts found</h3>
                <p class="text-gray-500 mb-6">You haven't submitted any posts yet. Create your first post to get started!
                </p>
                <a href="{{ route('posts.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg font-medium transition duration-200 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Post
                </a>
            </div>
        @endif
    </div>

    <!-- Post Details Modal -->
    <div id="postModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Post Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPostDetails(postId) {
            // This would typically make an AJAX call to get post details
            // For now, we'll just show a placeholder
            document.getElementById('modalTitle').textContent = 'Post Details';
            document.getElementById('modalContent').innerHTML = '<p>Loading post details...</p>';
            document.getElementById('postModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('postModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('postModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
