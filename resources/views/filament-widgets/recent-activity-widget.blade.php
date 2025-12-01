<x-filament-widgets::widget>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <x-heroicon-o-user-group class="w-5 h-5 mr-2 text-blue-500" />
                Recent User Registrations
            </h3>

            <div class="space-y-3">
                @forelse($this->getRecentUsers() as $user)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($user->verification_status === 'verified') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($user->verification_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif($user->verification_status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                {{ ucfirst($user->verification_status) }}
                            </span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No recent user registrations</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Posts -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <x-heroicon-o-document-text class="w-5 h-5 mr-2 text-green-500" />
                Recent Posts
            </h3>

            <div class="space-y-3">
                @forelse($this->getRecentPosts() as $post)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $post->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                by {{ $post->user->name ?? 'Unknown User' }}
                            </p>
                        </div>
                        <div class="text-right ml-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($post->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($post->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif($post->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                {{ ucfirst($post->status) }}
                            </span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No recent posts</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
