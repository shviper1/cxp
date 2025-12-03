<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $settings = $siteSettings ?? [];
        $defaultSiteName = $settings['site_name'] ?? config('app.name', 'CX Platform');
        $metaTitle = trim($__env->yieldContent('title')) ?: ($settings['seo_meta_title'] ?? $defaultSiteName);
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: ($settings['seo_meta_description'] ?? '');
        $metaKeywords = trim($__env->yieldContent('meta_keywords')) ?: ($settings['seo_meta_keywords'] ?? '');
        $ogImage = $settings['seo_og_image'] ?? null;
        $favicon = $settings['site_favicon'] ?? null;
    @endphp

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if(! blank($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ogImage)
        <meta property="og:image" content="{{ asset('storage/' . ltrim($ogImage, '/')) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($ogImage)
        <meta name="twitter:image" content="{{ asset('storage/' . ltrim($ogImage, '/')) }}">
    @endif

    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . ltrim($favicon, '/')) }}">
    @endif


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('meta')
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="relative flex min-h-screen flex-col">
        <x-site.header />

        <main id="main-content" class="flex-1">
            @yield('content')
        </main>

        <x-site.footer />
    </div>
</body>

</html>
