@php
    $settings = $siteSettings ?? [];
    $siteName = $settings['site_name'] ?? config('app.name', 'CX Platform');
    $siteDescription =
        $settings['site_description'] ??
        'A curated geo-directory that helps you explore opportunities across countries, states, and cities with confidence.';
    $aboutSummary = $settings['about_summary'] ?? $siteDescription;
    $aboutDetails = $settings['about_details'] ?? null;
    $siteLogo = $settings['site_logo'] ?? null;
    $siteLogoUrl = $siteLogo ? asset('storage/' . ltrim($siteLogo, '/')) : null;

    $contactEmail = $settings['contact_email'] ?? config('mail.from.address');
    $contactPhone = $settings['contact_phone'] ?? '+1 (000) 000-0000';
    $contactAddress = $settings['contact_address'] ?? null;

    $linkGroups = isset($footerLinks) ? $footerLinks : collect();
    if (!$linkGroups instanceof \Illuminate\Support\Collection) {
        $linkGroups = collect($linkGroups);
    }

    $aboutDetailsLines = collect($aboutDetails ? preg_split('/\r\n|\r|\n/', (string) $aboutDetails) : [])
        ->map(fn($line) => trim($line))
        ->filter(fn($line) => $line !== '');

    $normalizeLinks = function ($items) {
        return collect($items)
            ->map(function ($item) {
                if (is_array($item)) {
                    return [
                        'label' => $item['label'] ?? '',
                        'url' => $item['url'] ?? null,
                        'icon' => $item['icon'] ?? null,
                    ];
                }

                return [
                    'label' => $item->label ?? '',
                    'url' => $item->url ?? null,
                    'icon' => $item->icon_path ?? null,
                ];
            })
            ->filter(fn($link) => filled($link['label']));
    };

    $defaultPrimary = [
        ['label' => 'Locations', 'url' => url('/#locations')],
        ['label' => 'Insights', 'url' => url('/#insights')],
        ['label' => 'Create Post', 'url' => route('posts.create')],
        ['label' => 'Contact', 'url' => route('contact')],
    ];

    $primaryLinks = $normalizeLinks($linkGroups->get('primary', $defaultPrimary));
    $supportLinks = $normalizeLinks(
        $linkGroups->get('support', [['label' => 'Privacy Policy', 'url' => url('/privacy')]]),
    );
    $legalLinks = $normalizeLinks($linkGroups->get('legal', []));
    $socialLinkCollection = $normalizeLinks($linkGroups->get('social', []));
    if ($socialLinkCollection->isEmpty()) {
        $socialLinkCollection = collect(
            array_filter(
                [
                    ['label' => 'Facebook', 'url' => $settings['social_facebook'] ?? null],
                    ['label' => 'Twitter', 'url' => $settings['social_twitter'] ?? null],
                    ['label' => 'Instagram', 'url' => $settings['social_instagram'] ?? null],
                    ['label' => 'LinkedIn', 'url' => $settings['social_linkedin'] ?? null],
                ],
                fn($link) => filled($link['url']),
            ),
        );
    }

    $paymentLinks = $normalizeLinks($linkGroups->get('payment', []));
    $paymentHeading = $settings['footer_payment_heading'] ?? 'Supported payment methods';
    $showPayments = $paymentLinks->isNotEmpty();

    $footerHighlight = $settings['footer_highlight'] ?? 'Global reach';
    $footerHighlightDescription =
        $settings['footer_highlight_description'] ?? 'Designed for a global, SEO-friendly classifieds experience.';
    $footerHighlightLines = collect(
        $footerHighlightDescription ? preg_split('/\r\n|\r|\n/', (string) $footerHighlightDescription) : [],
    )
        ->map(fn($line) => trim($line))
        ->filter(fn($line) => $line !== '');

    $year = now()->year;
@endphp

<footer id="contact" class="border-t border-slate-200 bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                @if ($siteLogoUrl)
                    <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }} logo"
                        class="h-12 w-12 rounded-full object-cover object-center">
                @else
                    <span
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-900 text-lg font-bold text-white">{{ mb_substr($siteName, 0, 2) }}</span>
                @endif
                <div>
                    <p class="text-base font-semibold text-slate-900">{{ $siteName }}</p>
                    <p class="text-xs text-slate-500">{{ $siteDescription }}</p>
                </div>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $aboutSummary }}</p>
            @foreach ($aboutDetailsLines as $line)
                <p class="text-sm text-slate-500 leading-relaxed">{{ $line }}</p>
            @endforeach
        </div>

        <div>
            <p class="font-semibold text-slate-900">Explore</p>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                @foreach ($primaryLinks as $link)
                    <li>
                        <a href="{{ $link['url'] ?? '#' }}" class="hover:text-slate-900">{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="font-semibold text-slate-900">Support</p>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
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
                @foreach ($supportLinks as $link)
                    <li><a href="{{ $link['url'] ?? '#' }}" class="hover:text-slate-900">{{ $link['label'] }}</a></li>
                @endforeach
                @foreach ($legalLinks as $link)
                    <li><a href="{{ $link['url'] ?? '#' }}" class="hover:text-slate-900">{{ $link['label'] }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="space-y-5">
            @if ($socialLinkCollection->isNotEmpty())
                <div>
                    <p class="font-semibold text-slate-900">Stay connected</p>
                    <div class="mt-3 flex flex-wrap gap-3 text-sm text-slate-600">
                        @foreach ($socialLinkCollection as $link)
                            <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener"
                                class="hover:text-slate-900 transition">{{ $link['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($showPayments)
                <div>
                    <p class="font-semibold text-slate-900">{{ $paymentHeading }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        @foreach ($paymentLinks as $link)
                            <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener"
                                class="inline-flex h-10 w-16 items-center justify-center rounded-md border border-slate-200 bg-white p-2 shadow-sm transition hover:border-slate-300">
                                @if ($link['icon'])
                                    <img src="{{ asset('storage/' . ltrim($link['icon'], '/')) }}"
                                        alt="{{ $link['label'] }} logo" class="max-h-8 w-full object-contain">
                                @else
                                    <span class="text-xs font-medium text-slate-600">{{ $link['label'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-slate-200 bg-slate-50">
        <div
            class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-4 text-xs text-slate-500 sm:flex-row sm:px-6 lg:px-8">
            <p>&copy; {{ $year }} {{ $siteName }}. All rights reserved.</p>
            @if ($footerHighlight || $footerHighlightLines->isNotEmpty())
                <div class="text-center sm:text-right">
                    @if ($footerHighlight)
                        <p class="text-xs font-semibold text-slate-600">{{ $footerHighlight }}</p>
                    @endif
                    @foreach ($footerHighlightLines as $line)
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $line }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
