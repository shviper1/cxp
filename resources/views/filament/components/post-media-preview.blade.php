@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $mediaItems = collect($get('media') ?? [])
        ->map(function ($item, $index) {
            $path = null;
            $type = null;

            if (is_array($item)) {
                $path = $item['file_path'] ?? ($item[0] ?? null);
                $type = $item['type'] ?? null;
            } else {
                $path = $item;
            }

            if (is_array($path)) {
                $path = $path[0] ?? null;
            }

            if (!$type && $path) {
                $extension = Str::lower(pathinfo($path, PATHINFO_EXTENSION));
                $type = in_array($extension, ['mp4', 'mov', 'm4v', 'avi', 'mkv', 'webm']) ? 'video' : 'image';
            }

            return [
                'index' => $index + 1,
                'path' => $path,
                'type' => $type ?: 'image',
            ];
        })
        ->filter(fn($item) => filled($item['path']));
@endphp

@if ($mediaItems->isEmpty())
    <div
        class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-300">
        No media selected yet. Add photos or videos to preview them here before publishing.
    </div>
@else
    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="mb-3 flex items-center justify-between">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                <x-heroicon-o-photo class="mr-2 inline h-4 w-4 text-blue-500" />
                Media Preview
            </h4>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $mediaItems->count() }}
                file{{ $mediaItems->count() !== 1 ? 's' : '' }}</span>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($mediaItems as $item)
                @php
                    $url = Str::startsWith($item['path'], ['http://', 'https://'])
                        ? $item['path']
                        : Storage::disk('public')->url($item['path']);
                @endphp

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/60">
                    <div class="relative overflow-hidden rounded-md">
                        @if ($item['type'] === 'video')
                            <video src="{{ $url }}" class="h-40 w-full rounded-md object-cover" controls
                                preload="metadata" playsinline></video>
                        @else
                            <img src="{{ $url }}" alt="Media {{ $item['index'] }} preview"
                                class="h-40 w-full rounded-md object-cover" loading="lazy">
                        @endif
                    </div>

                    <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>#{{ $item['index'] }}</span>
                        <span class="capitalize">{{ $item['type'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
