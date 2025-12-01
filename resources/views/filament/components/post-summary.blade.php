<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
    <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
        <x-heroicon-o-eye class="w-5 h-5 mr-2 text-blue-600" />
        Post Preview
    </h4>

    <div class="space-y-3">
        <div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Title:</span>
            <p class="text-sm text-gray-900 dark:text-white font-medium">
                {{ $title ?: 'No title entered' }}
            </p>
        </div>

        <div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Description:</span>
            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                {{ $description ?: 'No description entered' }}
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Country:</span>
                <p class="text-sm text-gray-900 dark:text-white">
                    {{ $country }}
                </p>
            </div>

            <div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Category:</span>
                <p class="text-sm text-gray-900 dark:text-white">
                    {{ $category }}
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center text-sm">
                <x-heroicon-o-photo class="w-4 h-4 mr-1 text-green-600" />
                <span class="text-gray-600 dark:text-gray-400">
                    {{ $mediaCount }} media file{{ $mediaCount !== 1 ? 's' : '' }} uploaded
                </span>
            </div>

            <div class="flex items-center text-sm">
                <x-heroicon-o-check-circle class="w-4 h-4 mr-1 text-blue-600" />
                <span class="text-gray-600 dark:text-gray-400">
                    Ready to publish
                </span>
            </div>
        </div>
    </div>
</div>
