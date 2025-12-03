@php
    $settings = $siteSettings ?? [];
    $siteName = $settings['site_name'] ?? config('app.name', 'CX Platform');
    $siteTagline = $settings['site_description'] ?? 'Trusted geo-directory';
    $navigation = [
        ['label' => 'Browse', 'href' => url('/#locations')],
        ['label' => 'Cities', 'href' => url('/#cities')],
        ['label' => 'Insights', 'href' => url('/#insights')],
        ['label' => 'Contact', 'href' => route('contact')],
    ];
@endphp

<header
    class="border-b border-slate-200 bg-white/95 backdrop-blur supports-backdrop-filter:bg-white/75 sticky top-0 z-30">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-lg font-semibold text-slate-900">
                <span
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white font-bold">{{ mb_substr($siteName, 0, 2) }}</span>
                <div class="hidden sm:block">
                    <span>{{ $siteName }}</span>
                       <p class="text-xs font-normal text-slate-500">{{ \Illuminate\Support\Str::limit($siteTagline, 60) }}</p>
                </div>
            </a>
        </div>

        <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
            @foreach ($navigation as $item)
                <a href="{{ $item['href'] }}" class="transition hover:text-slate-900">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3 text-sm font-medium">
            @auth
                <!-- Authenticated User Menu -->
                <div class="relative">
                    <button data-dropdown-toggle="user-dropdown" aria-haspopup="true"
                            class="flex items-center space-x-2 text-slate-600 hover:text-slate-900 transition duration-200"
                            onclick="toggleDropdown('user-dropdown')">
                        <span>{{ Auth::user()->name }}</span>
                        @if(Auth::user()->isVerified())
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif(Auth::user()->isPendingVerification())
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="user-dropdown" data-dropdown-menu
                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200 transition-all duration-200 ease-out">

                        <a href="{{ route('dashboard.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0M8 5a2 2 0 012-2h4a2 2 0 012 2v0"></path>
                                </svg>
                                Dashboard
                            </div>
                        </a>

                        <a href="{{ route('dashboard.posts') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                My Posts
                            </div>
                        </a>

                        <a href="{{ route('dashboard.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </div>
                        </a>

                        @if(!Auth::user()->isVerified())
                            <a href="{{ route('dashboard.verification') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="{{ Auth::user()->isPendingVerification() ? 'text-yellow-600' : 'text-blue-600' }}">
                                        {{ Auth::user()->isPendingVerification() ? 'Verification Pending' : 'Verify Account' }}
                                    </span>
                                </div>
                            </a>
                        @endif

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <a href="{{ route('posts.create') }}"
                    class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-white transition hover:bg-slate-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Post
                </a>
            @else
                <!-- Guest User Menu -->
                <a href="{{ route('login') }}" class="text-slate-600 transition hover:text-slate-900">Log in</a>
                <a href="{{ route('register') }}"
                    class="hidden rounded-full border border-slate-300 px-4 py-2 text-slate-700 transition hover:border-slate-900 hover:text-slate-900 sm:inline-flex">Register</a>
                <a href="{{ url('/posts/create') }}"
                    class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-white transition hover:bg-slate-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Post
                </a>
            @endauth

            <!-- Dropdown JavaScript -->
            <script>
                function toggleDropdown(dropdownId) {
                    const dropdown = document.getElementById(dropdownId);
                    const isHidden = dropdown.classList.contains('hidden');

                    // Close all dropdowns first
                    document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                        menu.classList.add('hidden');
                    });

                    // Toggle the clicked dropdown
                    if (isHidden) {
                        dropdown.classList.remove('hidden');
                    }
                }

                // Close dropdowns when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('[data-dropdown-toggle]') && !event.target.closest('[data-dropdown-menu]')) {
                        document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                            menu.classList.add('hidden');
                        });
                    }
                });

                // Close dropdown on escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                            menu.classList.add('hidden');
                        });
                    }
                });
            </script>
        </div>
    </div>
</header>
