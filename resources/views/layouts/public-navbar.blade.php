@php
    $siteSettings = $site_settings ?? [];
    $branding = $siteSettings['site.branding'] ?? [];

    // Brand name (localized)
    $brandName = $branding['site_name_' . app()->getLocale()]
        ?? config('app.name', 'Laravel');

    // ✅ Correct key from SettingsController (logo_path)
    $logo = $branding['logo_path']
        ?? $branding['logo']
        ?? $branding['logo_light']
        ?? $branding['logo_' . app()->getLocale()]
        ?? null;
@endphp

<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 20"
     :class="{
        'bg-white shadow-md border-b border-gray-200': scrolled,
        'bg-white border-b border-gray-100': !scrolled
     }"
     class="bg-white sticky top-0 z-50 transition-all duration-300">

    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="flex h-20 items-center justify-between ">

            {{-- BRAND --}}
            <div class="flex col-span-2 items-center gap-2">
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 hover:opacity-90 transition-opacity duration-200">
                    @if($logo)
                        <img
                            src="{{ asset('storage/'.ltrim($logo, '/')) }}"
                            alt="{{ $brandName }}"
                            class="h-14 w-auto max-w-[800px] object-contain lg:h-15"
                            loading="eager"
                        >
                     
                    @else
                        <div class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-blue text-brand-blue lg:h-9 lg:w-9">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3" />
                            </svg>
                        </div>

                        <span class="text-base font-semibold text-gray-900 inline-block text-center">
                            {{ $brandName }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- DESKTOP NAV --}}
            <div class="hidden lg:flex lg:items-center lg:gap-2 lg:ml-6 lg:mr-4 lg:flex-nowrap">
                @php
                    // Basic nav items (exclude Home, News, About and Services which will be rendered separately)
                    // Each item includes an `icon` SVG to represent the link.
                    $nav = [
                        ['label' => __('common.nav.announcements'), 'href' => route('announcements.index'), 'active' => request()->routeIs('announcements.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>'],
                        ['label' => __('common.nav.downloads'), 'href' => route('downloads.index'), 'active' => request()->routeIs('downloads.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l4-4m-4 4l-4-4M21 21H3"/></svg>'],
                        ['label' => __('common.nav.contact'), 'href' => route('contact.create'), 'active' => request()->routeIs('contact.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0012.586 3H8a2 2 0 00-2 2v14l4-2 4 2 4-2 4 2V8z"/></svg>'],
                    ];

                    // Additional public links (appointments will be shown inside Services dropdown)

                    // Tenders public or admin (conditional)
                    if (Route::has('tenders.index')) {
                        $nav[] = ['label' => __('common.nav.tenders') ?? 'Tenders', 'href' => route('tenders.index'), 'active' => request()->routeIs('tenders.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6M9 8h6M4 6h16v12H4z"/></svg>'];
                    } elseif (Route::has('admin.tenders.index') && auth()->check() && auth()->user()->can('view tenders')) {
                        $nav[] = ['label' => __('common.nav.tenders') ?? 'Tenders', 'href' => route('admin.tenders.index'), 'active' => request()->routeIs('admin.tenders.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6M9 8h6M4 6h16v12H4z"/></svg>'];
                    }
                @endphp

                {{-- Home (desktop) --}}
                     <a href="{{ url('/') }}"
                         class="relative px-4 py-2 text-sm font-medium transition-colors duration-200 whitespace-nowrap {{ request()->is('/') ? 'text-brand-blue' : 'text-gray-700 hover:text-orange-600' }}">
                    @if(request()->is('/'))
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                    @endif
                    <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3"/></svg>' !!}</span>
                    <span class="ml-2">{{ __('Home') }}</span>
                </a>

                {{-- About dropdown (desktop) --}}
@php
    $aboutActive = request()->routeIs('pages.*') || request()->routeIs('staff.*');
@endphp

<div class="relative group">
    <button
        type="button"
        aria-haspopup="true"
        class="relative px-4 py-2 text-sm font-medium transition-colors duration-200 whitespace-nowrap {{ $aboutActive ? 'text-brand-blue' : 'text-gray-700 hover:text-orange-600' }}"
    >
            {{ __('common.nav.about') ?? 'About' }}
        <svg class="inline-block ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>

        @if($aboutActive)
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
        @endif
    </button>

    <div
        class="absolute left-0 mt-2 w-72 rounded-xl bg-white border border-gray-100 shadow-lg z-50
               opacity-0 translate-y-1 pointer-events-none
               group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
               group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto
               transition-all duration-150"
    >
        @if(Route::has('pages.about'))
            <a href="{{ route('pages.about') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50
                    {{ request()->routeIs('pages.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
                {{ __('common.nav.about') ?? 'About' }}
            </a>
        @endif

        @if(Route::has('staff.index'))
            <a href="{{ route('staff.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50
                    {{ request()->routeIs('staff.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 10-8 0 4 4 0 008 0z" />
                </svg>
                {{ __('common.nav.staff') ?? 'Staff' }}
            </a>
        @endif
    </div>
</div>

                {{-- News (desktop) --}}
                @if(Route::has('news.index'))
                          <a href="{{ route('news.index') }}"
                              class="relative px-4 py-2 text-sm font-medium transition-colors duration-200 whitespace-nowrap {{ request()->routeIs('news.*') ? 'text-brand-blue' : 'text-gray-700 hover:text-orange-600' }}">
                        @if(request()->routeIs('news.*'))
                            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                        <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2z"/></svg>' !!}</span>
                        <span class="ml-2">{{ __('common.nav.news') }}</span>
                    </a>
                @endif

                {{-- Services dropdown (desktop) --}}
@php
    $servicesActive = request()->routeIs('services.*') || request()->routeIs('service-requests.*') || request()->routeIs('appointments.*');
@endphp

<div class="relative group">
    <button
        type="button"
        aria-haspopup="true"
        class="relative px-4 py-2 text-sm font-medium transition-colors duration-200 whitespace-nowrap {{ $servicesActive ? 'text-brand-blue' : 'text-gray-700 hover:text-orange-600' }}"
    >
        <span class="inline-flex items-center mr-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V6a2 2 0 00-2-2H10a2 2 0 00-2 2v1M3 7h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
            </svg>
        </span>
        {{ __('common.nav.services') }}
        <svg class="inline-block ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>

        @if($servicesActive)
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
        @endif
    </button>

    <div
        class="absolute left-0 mt-2 w-72 rounded-xl bg-white border border-gray-100 shadow-lg z-50
               opacity-0 translate-y-1 pointer-events-none
               group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
               group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto
               transition-all duration-150"
    >
        <a href="{{ url('/services') }}"
           class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50
                {{ request()->routeIs('services.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            {{ __('common.nav.services') }}
        </a>

        @if(Route::has('service-requests.create'))
            <a href="{{ route('service-requests.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50
                    {{ request()->routeIs('service-requests.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16h8M8 12h8M8 8h8M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ __('common.nav.request_service') ?? 'Request Service' }}
            </a>
        @endif

        @if(Route::has('appointments.index'))
            <a href="{{ route('appointments.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50
                    {{ request()->routeIs('appointments.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M21 21H3a2 2 0 01-2-2V7a2 2 0 012-2h1" />
                </svg>
                {{ __('common.nav.appointments') ?? 'Appointments' }}
            </a>
        @endif
    </div>
</div>

{{-- duplicate About removed (single About dropdown kept earlier) --}}

                {{-- Render remaining simple links --}}
                @foreach($nav as $item)
                       <a href="{{ $item['href'] }}"
                           class="relative px-4 py-2 text-sm font-medium transition-colors duration-200 flex items-center whitespace-nowrap {{ $item['active'] ? 'text-brand-blue' : 'text-gray-700 hover:text-orange-600' }}">
                        @if($item['active'])
                            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                        <span class="inline-flex items-center text-current">{!! $item['icon'] ?? '' !!}</span>
                        <span class="ml-2">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- RIGHT ACTIONS --}}
            <div class="flex items-center gap-2 lg:ml-auto">

                {{-- LANGUAGE SWITCHER (DESKTOP) --}}
                <div class="hidden lg:flex items-center rounded-lg border border-gray-200 bg-white px-1 py-1">
                    @foreach(['am', 'en'] as $locale)
                        <form method="POST" action="{{ route('locale.switch', $locale) }}">
                            @csrf
                            <button type="submit"
                                class="rounded-md px-3 py-1 text-xs font-medium transition-colors duration-200
                                {{ app()->getLocale() === $locale
                                    ? 'bg-brand-blue text-white'
                                    : 'text-gray-600 hover:text-orange-600' }}">
                                {{ strtoupper($locale) }}
                            </button>
                        </form>
                    @endforeach
                </div>

                {{-- MOBILE TOGGLE --}}
                <button type="button"
                    class="lg:hidden inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white p-2 text-gray-700 hover:text-orange-600 transition-colors duration-200"
                        @click="open = !open"
                        :aria-expanded="open"
                        aria-label="Toggle navigation">
                    <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg x-show="open" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open" x-transition x-cloak class="lg:hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-2">
                {{-- Home (mobile) --}}
                <a href="{{ url('/') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                    <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3"/></svg>' !!}</span>
                    <span class="ml-3">{{ __('Home') }}</span>
                </a>

                {{-- About (mobile) --}}
                @if(Route::has('pages.about'))
                    <a href="{{ route('pages.about') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                        <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>' !!}</span>
                        <span class="ml-3">{{ __('common.nav.about') ?? 'About' }}</span>
                    </a>
                @endif

                @if(Route::has('staff.index'))
                    <a href="{{ route('staff.index') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                        <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>' !!}</span>
                        <span class="ml-3">{{ __('common.nav.staff') ?? 'Staff' }}</span>
                    </a>
                @endif

                {{-- News (mobile) --}}
                @if(Route::has('news.index'))
                    <a href="{{ route('news.index') }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                        <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2z"/></svg>' !!}</span>
                        <span class="ml-3">{{ __('common.nav.news') }}</span>
                    </a>
                @endif

                {{-- Services (mobile) --}}
                <div class="space-y-1">
                    <div class="px-3 py-2 text-sm font-medium text-gray-700">
                        <span class="inline-flex items-center mr-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V6a2 2 0 00-2-2H10a2 2 0 00-2 2v1M3 7h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                        </span>
                        {{ __('common.nav.services') }}
                    </div>
                    <div class="space-y-1 px-3">
                        <a href="{{ url('/services') }}" @click="open = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">{{ __('common.nav.services') }}</a>
                        @if(Route::has('service-requests.create'))
                            <a href="{{ route('service-requests.create') }}" @click="open = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">{{ __('common.nav.request_service') ?? 'Request Service' }}</a>
                        @endif
                        @if(Route::has('appointments.index'))
                            <a href="{{ route('appointments.index') }}" @click="open = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-orange-600">{{ __('common.nav.appointments') ?? 'Appointments' }}</a>
                        @endif
                    </div>
                </div>

                {{-- Remaining links --}}
                @foreach($nav as $item)
                    <a href="{{ $item['href'] }}" @click="open = false" class="block rounded-lg px-3 py-2.5 text-sm font-medium transition-colors duration-200 flex items-center {{ $item['active'] ? 'text-brand-blue' : 'text-gray-700 hover:bg-gray-50 hover:text-orange-600' }}">
                        <span class="inline-flex items-center">{!! $item['icon'] ?? '' !!}</span>
                        <span class="ml-3">{{ $item['label'] }}</span>
                    </a>
                @endforeach

            <div class="flex gap-2 pt-3">
                @foreach(['am', 'en'] as $locale)
                    <form method="POST" action="{{ route('locale.switch', $locale) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                @click="open = false"
                                class="w-full rounded-lg px-3 py-2 text-xs font-medium transition-colors duration-200
                                {{ app()->getLocale() === $locale
                                    ? 'bg-brand-blue text-white'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ strtoupper($locale) }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</nav>

<style>
    /* Smooth transitions */
    nav a, nav button {
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Improved focus styles */
    nav a:focus, nav button:focus {
        outline: 2px solid rgba(59, 130, 246, 0.3);
        outline-offset: 1px;
    }

    /* Active link indicator animation */
    nav a.active-indicator div {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-50%) scaleX(0);
        }
        to {
            transform: translateX(-50%) scaleX(1);
        }
    }
</style>

<script>
    // Add smooth hover effect
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('nav a, nav button').forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
            });
            
            el.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>