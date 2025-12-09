@php
    use Illuminate\Support\Str;

    $statusLabel = $status ? Str::headline($status) : 'Pending Review';
    $statusColor = match ($status) {
        'approved' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-300',
        'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-300',
        default => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
    };

    $paymentLabel = $paymentStatus ? Str::headline($paymentStatus) : 'Free';
    $paymentColor = match ($paymentStatus) {
        'paid' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300',
        'pending' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-300',
        default => 'bg-slate-100 text-slate-700 dark:bg-slate-500/10 dark:text-slate-300',
    };
@endphp

<div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
    <h4 class="mb-3 flex items-center text-sm font-semibold text-gray-900 dark:text-white">
        <x-heroicon-o-eye class="mr-2 h-5 w-5 text-blue-600" />
        Post Preview
    </h4>

    <div class="space-y-4">
        <div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Title</span>
            <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $title ?: 'No title entered yet' }}
            </p>
        </div>

        <div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</span>
            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                {{ $description ?: 'Add a detailed description so buyers know what to expect.' }}
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <span
                    class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Location</span>
                <p class="text-sm text-gray-900 dark:text-white">
                    {{ collect([$city, $state, $country])->filter()->implode(', ') ?:'Not selected' }}
                </p>
            </div>

            <div>
                <span
                    class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Category</span>
                <p class="text-sm text-gray-900 dark:text-white">
                    {{ collect([$section, $category])->filter()->implode(' · ') ?:'Not selected' }}
                </p>
            </div>

            <div>
                <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Contact
                    Email</span>
                <p class="text-sm text-gray-900 dark:text-white">
                    {{ $contactEmail ?: 'Not provided' }}
                </p>
            </div>

            <div>
                <span class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</span>
                <p class="text-sm text-gray-900 dark:text-white">
                    {{ $contactPhone ?: 'Not provided' }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 border-t border-gray-200 pt-3 text-xs dark:border-gray-600">
            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 font-medium {{ $statusColor }}">
                <x-heroicon-o-check-circle class="h-4 w-4" />
                {{ $statusLabel }}
            </span>
            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 font-medium {{ $paymentColor }}">
                <x-heroicon-o-credit-card class="h-4 w-4" />
                {{ $paymentLabel }}
            </span>
            <span
                class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-700 dark:bg-slate-500/10 dark:text-slate-300">
                <x-heroicon-o-photo class="h-4 w-4" />
                {{ $mediaCount }} media file{{ $mediaCount !== 1 ? 's' : '' }}
            </span>
        </div>
    </div>
</div>
