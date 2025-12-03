@php
    $settings = $siteSettings ?? [];
    $siteName = $settings['site_name'] ?? config('app.name', 'CX Platform');
    $siteDescription =
        $settings['site_description'] ??
        'A curated geo-directory that helps you explore opportunities across countries, states, and cities with confidence.';
    $contactEmail = $settings['contact_email'] ?? config('mail.from.address');
    $contactPhone = $settings['contact_phone'] ?? '+1 (000) 000-0000';
    $contactAddress = $settings['contact_address'] ?? null;
    $socialLinks = array_filter([
        'Facebook' => $settings['social_facebook'] ?? null,
        'Twitter' => $settings['social_twitter'] ?? null,
        'Instagram' => $settings['social_instagram'] ?? null,
        'LinkedIn' => $settings['social_linkedin'] ?? null,
    ]);
    $year = now()->year;
@endphp

<footer id="contact" class="border-t border-slate-200 bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div>
            <p class="text-lg font-semibold text-slate-900">{{ $siteName }}</p>
            <p class="mt-3 text-sm text-slate-600">{{ $siteDescription }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm text-slate-600">
            <div>
                <p class="font-semibold text-slate-900">Links</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="{{ url('/#locations') }}" class="hover:text-slate-900">Locations</a></li>
                    <li><a href="{{ url('/#insights') }}" class="hover:text-slate-900">Insights</a></li>
                    <li><a href="{{ route('posts.create') }}" class="hover:text-slate-900">Create Post</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-slate-900">Contact</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-slate-900">Support</p>
                <ul class="mt-3 space-y-2">
                    @if ($contactEmail)
                        <li><a href="mailto:{{ $contactEmail }}" class="hover:text-slate-900">{{ $contactEmail }}</a>
                        </li>
                    @endif
                    @if ($contactPhone)
                        <li><a href="tel:{{ preg_replace('/[^\d+]/', '', $contactPhone) }}"
                                class="hover:text-slate-900">{{ $contactPhone }}</a></li>
                    @endif
                    @if ($contactAddress)
                        <li><span class="block leading-relaxed">{{ $contactAddress }}</span></li>
                    @endif
                    <li><a href="{{ url('/privacy') }}" class="hover:text-slate-900">Privacy</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col justify-between">
            <p class="text-sm text-slate-500">&copy; {{ $year }} {{ $siteName }}. All rights reserved.</p>
            @if (!empty($socialLinks))
                <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-600">
                    @foreach ($socialLinks as $label => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                            class="hover:text-slate-900 transition">{{ $label }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
