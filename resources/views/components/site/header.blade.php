@php
    $navigation = [
        ['label' => 'Browse', 'href' => url('/#locations')],
        ['label' => 'Cities', 'href' => url('/#cities')],
        ['label' => 'Insights', 'href' => url('/#insights')],
        ['label' => 'Contact', 'href' => url('/#contact')],
    ];
@endphp

<header
    class="border-b border-slate-200 bg-white/95 backdrop-blur supports-backdrop-filter:bg-white/75 sticky top-0 z-30">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-lg font-semibold text-slate-900">
                <span
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white font-bold">{{ mb_substr(config('app.name', 'CX'), 0, 2) }}</span>
                <div class="hidden sm:block">
                    <span>{{ config('app.name', 'CX Platform') }}</span>
                    <p class="text-xs font-normal text-slate-500">Trusted geo-directory</p>
                </div>
            </a>
        </div>

        <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
            @foreach ($navigation as $item)
                <a href="{{ $item['href'] }}" class="transition hover:text-slate-900">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3 text-sm font-medium">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('posts.create') }}"
                        class="hidden text-slate-600 transition hover:text-slate-900 sm:inline-flex">Dashboard</a>
                    <a href="{{ route('posts.create') }}"
                        class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-white transition hover:bg-slate-800">New
                        Post</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 transition hover:text-slate-900">Log in</a>
                    <a href="{{ route('register') }}"
                        class="hidden rounded-full border border-slate-300 px-4 py-2 text-slate-700 transition hover:border-slate-900 hover:text-slate-900 sm:inline-flex">Register</a>
                @endauth
            @else
                <a href="{{ url('/posts/create') }}"
                    class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-white transition hover:bg-slate-800">New
                    Post</a>
            @endif
        </div>
    </div>
</header>
