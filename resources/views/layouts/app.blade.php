<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
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
