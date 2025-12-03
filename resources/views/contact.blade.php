@extends('layouts.app')

@php
    $settings = $siteSettings ?? [];
    $siteName = $settings['site_name'] ?? config('app.name', 'CX Platform');
@endphp

@section('title', $pageTitle . ' | ' . $siteName)
@section('meta_description', $pageDescription)
@section('meta_keywords', $settings['seo_meta_keywords'] ?? 'contact, support, help')

@section('content')
<section class="bg-slate-50 py-16">
    <div class="mx-auto flex max-w-5xl flex-col gap-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-medium uppercase tracking-widest text-indigo-600">Get in touch</p>
            <h1 class="mt-3 text-4xl font-bold text-slate-900">{{ $pageTitle }}</h1>
            <p class="mt-4 text-lg leading-relaxed text-slate-600">{{ $pageDescription }}</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-5">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Contact details</h2>
                    <div class="mt-4 space-y-4">
                        @forelse($contactDetails as $detail)
                            @if(! empty($detail['value']))
                                <div>
                                    <p class="text-sm font-medium uppercase tracking-wide text-slate-500">{{ $detail['label'] }}</p>
                                    @if(! empty($detail['href']))
                                        <a href="{{ $detail['href'] }}" class="mt-1 inline-flex items-center text-base font-medium text-indigo-600 hover:text-indigo-700">
                                            {{ $detail['value'] }}
                                        </a>
                                    @else
                                        <p class="mt-1 text-base text-slate-700">{{ $detail['value'] }}</p>
                                    @endif
                                </div>
                            @endif
                        @empty
                            <p class="text-sm text-slate-500">We are currently updating our contact information.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Follow us</h2>
                    <p class="mt-2 text-sm text-slate-600">Stay connected through our social channels.</p>
                    @php
                        $socialLinks = array_filter([
                            'Facebook' => $settings['social_facebook'] ?? null,
                            'Twitter' => $settings['social_twitter'] ?? null,
                            'Instagram' => $settings['social_instagram'] ?? null,
                            'LinkedIn' => $settings['social_linkedin'] ?? null,
                        ]);
                    @endphp
                    @if(! empty($socialLinks))
                        <div class="mt-4 flex flex-wrap gap-3">
                            @foreach($socialLinks as $label => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-indigo-500 hover:text-indigo-600">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500">Social profiles will be shared soon.</p>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Send us a message</h2>
                    <p class="mt-2 text-sm text-slate-600">Fill out the form below and we&rsquo;ll get back to you within 1-2 business days.</p>

                    @if (session('message'))
                        <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="mt-6 space-y-6">
                        @csrf
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700">Phone (optional)</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-slate-700">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
                            <textarea id="message" name="message" rows="5" required class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 sm:w-auto">
                            Send message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
