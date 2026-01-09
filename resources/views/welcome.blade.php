@extends('layouts.app')

@php
    $pageTitle = 'Discover trusted listings by country, state, and city';
    $pageDescription =
        'Browse curated listings with location-aware filters spanning countries, states, and cities to find the perfect opportunity.';
    $pageKeywords = 'directory, geo listings, countries, states, cities, classifieds';
    $siteTitle = $siteSettings['site_name'] ?? config('app.name', 'CX Platform');
@endphp

@section('title', $pageTitle . ' | ' . $siteTitle)
@section('meta_description', $pageDescription)
@section('meta_keywords', $pageKeywords)

@section('content')
    @php
        $searchTerm = strtolower(trim(request('search', '')));
        $selectedCountryId = request('country');
        $countryCount = $countries->count();
        $stateCount = $countries->flatMap->states->count();
        $cityCount = $countries->flatMap->states->flatMap->cities->count();
    @endphp

    {{-- <section class="text-white bg-linear-to-b from-slate-900 via-slate-900 to-slate-800">
        <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Global reach</p>
                <h1 class="mt-4 text-4xl font-semibold leading-tight sm:text-5xl">
                    Map-ready, SEO-friendly listings for every major region
                </h1>
                <p class="mt-6 text-lg text-slate-200">
                    Jump straight into the data you need. Filter by country, scan top states, and view the cities that
                    matter
                    without leaving the page.
                </p>
                <div class="flex flex-wrap gap-6 mt-10 text-sm">
                    <div>
                        <p class="text-3xl font-semibold">{{ number_format($countryCount) }}+</p>
                        <p class="text-slate-400">Countries</p>
                    </div>
                    <div>
                        <p class="text-3xl font-semibold">{{ number_format($stateCount) }}</p>
                        <p class="text-slate-400">States & regions</p>
                    </div>
                    <div>
                        <p class="text-3xl font-semibold">{{ number_format($cityCount) }}</p>
                        <p class="text-slate-400">Cities covered</p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <section id="filters" class="py-10 bg-white shadow-sm">
        <div class="flex flex-col gap-6 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <form method="GET" action="{{ url('/') }}" class="grid gap-4 md:grid-cols-[2fr,1fr,auto,auto]">
                <label class="flex flex-col text-sm font-medium text-slate-700">
                    Keyword
                    <input type="search" name="search" value="{{ request('search') }}"
                        placeholder="Search country, state, or city"
                        class="px-4 py-2 mt-1 text-base border rounded-lg border-slate-200 text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200" />
                </label>
                <label class="flex flex-col text-sm font-medium text-slate-700">
                    Country
                    <select name="country"
                        class="px-4 py-2 mt-1 text-base border rounded-lg border-slate-200 text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200">
                        <option value="">All countries</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @selected(request('country') == $country->id)>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 mt-auto text-sm font-semibold text-white transition rounded-full bg-slate-900 hover:bg-slate-800">
                    Apply filters
                </button>
                @if (request()->hasAny(['search', 'country']))
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center justify-center px-6 py-3 mt-auto text-sm font-semibold transition border rounded-full border-slate-200 text-slate-700 hover:border-slate-900 hover:text-slate-900">
                        Reset
                    </a>
                @endif
            </form>
            {{-- <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Filters update instantly for SEO-friendly URLs.</p> --}}
        </div>
    </section>

    <section id="locations" class="py-16 bg-slate-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Coverage</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Location intelligence</h2>
                    <p class="text-slate-600">Drill down into the hierarchy to understand where your next post belongs.</p>
                </div>
                <a href="#filters" class="text-sm font-semibold text-slate-900">Adjust filters &rarr;</a>
            </div>

            <div class="mt-10 space-y-10">
                @php
                    $displayedCountries = false;
                @endphp
                @foreach ($countries as $country)
                    @php
                        $stateBlocks = [];
                        foreach ($country->states as $state) {
                            $stateName = $state->name;
                            $stateMatchesSearch =
                                $searchTerm === '' ? true : str_contains(strtolower($stateName), $searchTerm);
                            $citiesToDisplay = $state->cities;

                            if ($searchTerm !== '') {
                                $citiesToDisplay = $state->cities->filter(function ($city) use ($searchTerm) {
                                    return str_contains(strtolower($city->name), $searchTerm);
                                });
                            }

                            if ($stateMatchesSearch || $citiesToDisplay->isNotEmpty()) {
                                $stateBlocks[] = [
                                    'state' => $stateName,
                                    'cities' => $searchTerm === '' ? $state->cities : $citiesToDisplay,
                                ];
                            }
                        }

                        $countryMatchesDropdown =
                            empty($selectedCountryId) || (string) $country->id === (string) $selectedCountryId;
                        $shouldDisplayCountry = $countryMatchesDropdown && !empty($stateBlocks);
                    @endphp

                    @continue(!$shouldDisplayCountry)
                    @php
                        $displayedCountries = true;
                    @endphp

                    <article class="p-8 border shadow-sm rounded-3xl border-slate-200 bg-white/80">
                        <div
                            class="flex flex-col gap-4 pb-6 border-b border-slate-100 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Country</p>
                                <h3 class="text-2xl font-semibold text-slate-900">{{ $country->name }}</h3>
                                <p class="text-sm text-slate-500">{{ count($stateBlocks) }} states featured</p>
                            </div>
                            <a href="{{ route('locations.country', $country) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold transition border rounded-full border-slate-200 text-slate-700 hover:border-slate-900 hover:text-slate-900">
                                Browse {{ $country->name }}
                            </a>
                        </div>

                        <div class="grid gap-4 mt-6 md:grid-cols-2">
                            @foreach ($stateBlocks as $block)
                                <div class="p-5 border rounded-2xl border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <p class="text-base font-semibold text-[#EE6498]">{{ $block['state'] }}</p>
                                        <span
                                            class="text-xs tracking-wide uppercase text-slate-400">{{ $block['cities']->count() }}
                                            cities</span>
                                    </div>
                                    <ul class="mt-3 space-y-2 text-sm text-[#009dd0]">
                                        @forelse ($block['cities'] as $city)
                                            <li class="flex items-center gap-2">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                                                <a href="{{ route('locations.country', $country) }}"
                                                    class="transition-colors hover:text-slate-900 hover:underline">
                                                    {{ $city->name }}
                                                </a>
                                            </li>
                                        @empty
                                            <li class="text-slate-400">No cities available.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endforeach

                @if (!$displayedCountries)
                    <div class="p-10 text-center bg-white border border-dashed rounded-3xl border-slate-300">
                        <p class="text-lg font-semibold text-slate-900">No locations found</p>
                        <p class="mt-2 text-sm text-slate-500">Try adjusting your filters or clearing the search keyword.
                        </p>
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center px-6 py-3 mt-4 text-sm font-semibold text-white transition rounded-full bg-slate-900 hover:bg-slate-800">Reset
                            filters</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
