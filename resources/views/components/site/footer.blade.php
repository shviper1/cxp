@php
    $year = now()->year;
@endphp

<footer id="contact" class="border-t border-slate-200 bg-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div>
            <p class="text-lg font-semibold text-slate-900">{{ config('app.name', 'CX Platform') }}</p>
            <p class="mt-3 text-sm text-slate-600">A curated geo-directory that helps you explore opportunities across
                countries, states, and cities with confidence.</p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm text-slate-600">
            <div>
                <p class="font-semibold text-slate-900">Links</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="{{ url('/#locations') }}" class="hover:text-slate-900">Locations</a></li>
                    <li><a href="{{ url('/#insights') }}" class="hover:text-slate-900">Insights</a></li>
                    <li><a href="{{ route('posts.create') }}" class="hover:text-slate-900">Create Post</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-slate-900">Support</p>
                <ul class="mt-3 space-y-2">
                    <li><a href="mailto:support@{{ str_replace(['https://', 'http://'], '', request()->getHost()) }}" class="hover:text-slate-900">Email us</a></li>
                    <li><a href="tel:+10000000000" class="hover:text-slate-900">+1 (000) 000-0000</a></li>
                    <li><a href="{{ url('/privacy') }}" class="hover:text-slate-900">Privacy</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col justify-between">
            <p class="text-sm text-slate-500">&copy; {{ $year }} {{ config('app.name', 'CX Platform') }}. All
                rights reserved.</p>
            <div class="mt-3 flex gap-3 text-sm text-slate-600">
                <a href="https://twitter.com" target="_blank" rel="noopener" class="hover:text-slate-900">Twitter</a>
                <a href="https://www.linkedin.com" target="_blank" rel="noopener"
                    class="hover:text-slate-900">LinkedIn</a>
                <a href="https://instagram.com" target="_blank" rel="noopener"
                    class="hover:text-slate-900">Instagram</a>
            </div>
        </div>
    </div>
</footer>
