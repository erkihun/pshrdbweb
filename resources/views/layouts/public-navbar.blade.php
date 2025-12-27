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
     @click.away="open = false"
     :class="{
        'bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/80 shadow-sm border-b border-gray-200': scrolled,
        'bg-white border-b border-gray-100': !scrolled
     }"
     class="bg-white sticky top-0 z-50 transition-all duration-300 public-navbar">

    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-4 sm:px-6 lg:px-10">
        <div class="flex h-20 items-center justify-between">

            {{-- BRAND --}}
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}"
                   class="group inline-flex items-center gap-3 rounded-xl px-2 py-1 hover:bg-gray-50 transition">
                    @if($logo)
                        <img
                            src="{{ asset('storage/'.ltrim($logo, '/')) }}"
                            alt="{{ $brandName }}"
                            class="h-12 w-auto max-w-[800px] object-contain lg:h-14"
                            loading="eager"
                        >
                    @else
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-blue text-white shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3" />
                            </svg>
                        </div>

                        <span class="text-base font-semibold tracking-tight text-gray-900">
                            {{ $brandName }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- DESKTOP NAV --}}
            <div class="hidden lg:flex lg:items-center lg:gap-1 lg:ml-6 lg:mr-4 lg:flex-nowrap">
                @php
                    $nav = [
                        ['label' => __('common.nav.announcements'), 'href' => route('announcements.index'), 'active' => request()->routeIs('announcements.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>'],
                        ['label' => __('common.nav.downloads'), 'href' => route('downloads.index'), 'active' => request()->routeIs('downloads.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l4-4m-4 4l-4-4M21 21H3"/></svg>'],
                        ['label' => __('common.nav.contact'), 'href' => route('contact.create'), 'active' => request()->routeIs('contact.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0012.586 3H8a2 2 0 00-2 2v14l4-2 4 2 4-2 4 2V8z"/></svg>'],
                    ];

                    if (Route::has('tenders.index')) {
                        $nav[] = ['label' => __('common.nav.tenders') ?? 'Tenders', 'href' => route('tenders.index'), 'active' => request()->routeIs('tenders.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6M9 8h6M4 6h16v12H4z"/></svg>'];
                    } elseif (Route::has('admin.tenders.index') && auth()->check() && auth()->user()->can('view tenders')) {
                        $nav[] = ['label' => __('common.nav.tenders') ?? 'Tenders', 'href' => route('admin.tenders.index'), 'active' => request()->routeIs('admin.tenders.*'), 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6M9 8h6M4 6h16v12H4z"/></svg>'];
                    }
                @endphp

                {{-- Shared desktop link UI --}}
                @php
                    $desktopLinkBase = 'relative inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
                    $desktopLinkHover = 'hover:bg-gray-50 hover:text-orange-600 hover:-translate-y-[1px]';
                @endphp

                {{-- Home (desktop) --}}
                <a href="{{ url('/') }}"
                   class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ request()->is('/') ? 'text-brand-blue' : 'text-gray-700' }}">
                    @if(request()->is('/'))
                        <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                    @endif
                    <span class="inline-flex items-center">{!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3"/></svg>' !!}</span>
                    <span>{{ __('common.nav.home') }}</span>
                </a>

                {{-- About dropdown (desktop) --}}
                @php $aboutActive = request()->routeIs('pages.*') || request()->routeIs('staff.*'); @endphp

                    <div class="relative group">
                        <button type="button"
                                aria-haspopup="true"
                                class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $aboutActive ? 'text-brand-blue' : 'text-gray-700' }} font-abyssinica">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                            <span>{{ __('common.nav.about') ?? 'About' }}</span>
                        </span>

                        <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        @if($aboutActive)
                            <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                    </button>

                        <div class="absolute left-0 mt-2 w-80 rounded-2xl bg-white border border-gray-100 shadow-xl z-50
                                opacity-0 translate-y-1 pointer-events-none
                                group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
                                group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto
                                transition-all duration-150">
                            <div class="p-2 font-abyssinica">
                            @if(Route::has('pages.about'))
                                <a href="{{ route('pages.about') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('pages.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                        </svg>
                                    </span>
                                    <span>{{ __('common.nav.about') ?? 'About' }}</span>
                                </a>
                            @endif

                            @if(Route::has('staff.index'))
                                <a href="{{ route('staff.index') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('staff.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 10-8 0 4 4 0 008 0z" />
                                        </svg>
                                    </span>
                                   <span>{{ __('common.nav.staff') ?? 'Staff' }}</span>
                               </a>
                           @endif

                            @if(Route::has('public-servants.dashboard'))
                                <a href="{{ route('public-servants.dashboard') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('public-servants.dashboard') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6v6l4 2m2-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <span>{{ __('common.nav.public_servant_dashboard') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- News (desktop) --}}
                @if(Route::has('news.index'))
                    <a href="{{ route('news.index') }}"
                       class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ request()->routeIs('news.*') ? 'text-brand-blue' : 'text-gray-700' }}">
                        @if(request()->routeIs('news.*'))
                            <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                        <span class="inline-flex items-center gap-2">
                            {!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2z"/></svg>' !!}
                            <span>{{ __('common.nav.news') }}</span>
                        </span>
                    </a>
                @endif

                {{-- Services dropdown (desktop) --}}
                @php
                    $servicesActive = request()->routeIs('services.*') || request()->routeIs('service-requests.*') || request()->routeIs('appointments.*');
                @endphp

                    <div class="relative group">
                        <button type="button"
                                aria-haspopup="true"
                                class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $servicesActive ? 'text-brand-blue' : 'text-gray-700' }} font-abyssinica">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7V6a2 2 0 00-2-2H10a2 2 0 00-2 2v1M3 7h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            <span>{{ __('common.nav.services') }}</span>
                        </span>

                        <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        @if($servicesActive)
                            <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                    </button>

                        <div class="absolute left-0 mt-2 w-80 rounded-2xl bg-white border border-gray-100 shadow-xl z-50
                                opacity-0 translate-y-1 pointer-events-none
                                group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
                                group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto
                                transition-all duration-150">
                        <div class="p-2 font-abyssinica">
                            <a href="{{ url('/services') }}"
                               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                      {{ request()->routeIs('services.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </span>
                                <span>{{ __('common.nav.services') }}</span>
                            </a>

                            @if(Route::has('service-requests.create'))
                                <a href="{{ route('service-requests.create') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('service-requests.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 16h8M8 12h8M8 8h8M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <span>{{ __('common.nav.request_service') ?? 'Request Service' }}</span>
                                </a>
                            @endif

                            @if(Route::has('appointments.index'))
                                <a href="{{ route('appointments.index') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('appointments.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M21 21H3a2 2 0 01-2-2V7a2 2 0 012-2h1" />
                                        </svg>
                                    </span>
                                    <span>{{ __('common.nav.appointments') ?? 'Appointments' }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Remaining simple links --}}
                @foreach($nav as $item)
                    <a href="{{ $item['href'] }}"
                       class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $item['active'] ? 'text-brand-blue' : 'text-gray-700' }}">
                        @if($item['active'])
                            <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                        <span class="inline-flex items-center gap-2">
                            <span class="inline-flex items-center text-current">{!! $item['icon'] ?? '' !!}</span>
                            <span>{{ $item['label'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- RIGHT ACTIONS --}}
            <div class="flex items-center gap-2 lg:ml-auto">

                {{-- LANGUAGE SWITCHER (DESKTOP) --}}
                <div class="hidden lg:flex items-center rounded-xl border border-gray-200 bg-white p-1 shadow-sm">
                    @php
                        $localeMeta = [
                            'am' => ['label' => 'አማ', 'title' => __('Amharic')],
                            'en' => ['label' => 'EN', 'title' => __('English')],
                        ];
                    @endphp

                    @foreach(['am', 'en'] as $locale)
                        <form method="POST" action="{{ route('locale.switch', $locale) }}">
                            @csrf
                            <button type="submit"
                                    title="{{ $localeMeta[$locale]['title'] }}"
                                    class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold transition
                                    {{ app()->getLocale() === $locale
                                        ? 'bg-brand-blue text-white shadow-sm'
                                        : 'text-gray-700 hover:bg-gray-50 hover:text-orange-600' }}">
                                @if($locale === 'am')
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                        <rect x="2" y="5" width="20" height="14" rx="2" fill="currentColor" opacity=".12"></rect>
                                        <rect x="3" y="6" width="18" height="4" fill="#22c55e"></rect>
                                        <rect x="3" y="10" width="18" height="4" fill="#fbbf24"></rect>
                                        <rect x="3" y="14" width="18" height="4" fill="#ef4444"></rect>
                                    </svg>
                                @else
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 110-18 9 9 0 010 18z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2.5 2.2 4 5.4 4 9s-1.5 6.8-4 9c-2.5-2.2-4-5.4-4-9s1.5-6.8 4-9z" />
                                    </svg>
                                @endif
                                <span>{{ $localeMeta[$locale]['label'] }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>

                {{-- MOBILE TOGGLE --}}
                <button type="button"
                        class="lg:hidden inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-2.5 text-gray-700 shadow-sm hover:bg-gray-50 hover:text-orange-600 transition relative z-50"
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
    <template x-if="open">
        <div class="lg:hidden">
            <div class="fixed inset-0 z-30 bg-black/30 backdrop-blur-sm" @click="open = false"></div>
            <div x-show="open" x-transition x-cloak class="relative z-40 border-t border-gray-100 bg-white">
                <div class="px-4 py-4 space-y-2">

                    @php
                        $mobileLinkBase = 'block rounded-xl px-3 py-2.5 text-sm font-semibold transition';
                        $mobileLinkHover = 'hover:bg-gray-50 hover:text-orange-600';
                    @endphp

                    {{-- Home (mobile) --}}
                    <a href="{{ url('/') }}" @click="open = false"
                       class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                        <span class="inline-flex items-center gap-3">
                            {!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0v9a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h3"/></svg>' !!}
                            <span>{{ __('common.nav.home') }}</span>
                        </span>
                    </a>

                    {{-- About (mobile) --}}
                    @if(Route::has('pages.about'))
                        <a href="{{ route('pages.about') }}" @click="open = false"
                           class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                            <span class="inline-flex items-center gap-3">
                                {!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>' !!}
                                <span>{{ __('common.nav.about') ?? 'About' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(Route::has('staff.index'))
                        <a href="{{ route('staff.index') }}" @click="open = false"
                           class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                            <span class="inline-flex items-center gap-3">
                                {!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>' !!}
                                <span>{{ __('common.nav.staff') ?? 'Staff' }}</span>
                            </span>
                        </a>
                    @endif

                    @if(Route::has('public-servants.dashboard'))
                        <a href="{{ route('public-servants.dashboard') }}" @click="open = false"
                           class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                            <span class="inline-flex items-center gap-3">
                                {!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m2-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' !!}
                                <span>{{ __('common.nav.public_servant_dashboard') }}</span>
                            </span>
                        </a>
                    @endif

                    {{-- News (mobile) --}}
                    @if(Route::has('news.index'))
                        <a href="{{ route('news.index') }}" @click="open = false"
                           class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                            <span class="inline-flex items-center gap-3">
                                {!! '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2z"/></svg>' !!}
                                <span>{{ __('common.nav.news') }}</span>
                            </span>
                        </a>
                    @endif

                    {{-- Services (mobile) --}}
                    <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-3 font-abyssinica">
                        <div class="flex items-center gap-2 px-1 pb-2 text-sm font-semibold text-gray-900">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7V6a2 2 0 00-2-2H10a2 2 0 00-2 2v1M3 7h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            <span>{{ __('common.nav.services') }}</span>
                        </div>

                        <div class="space-y-1">
                            <a href="{{ url('/services') }}" @click="open = false"
                               class="block rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                {{ __('common.nav.services') }}
                            </a>

                            @if(Route::has('service-requests.create'))
                                <a href="{{ route('service-requests.create') }}" @click="open = false"
                                   class="block rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                    {{ __('common.nav.request_service') ?? 'Request Service' }}
                                </a>
                            @endif

                            @if(Route::has('appointments.index'))
                                <a href="{{ route('appointments.index') }}" @click="open = false"
                                   class="block rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                    {{ __('common.nav.appointments') ?? 'Appointments' }}
                                </a>
                            @endif
                        </div>
                    </div>

                {{-- Remaining links --}}
                @foreach($nav as $item)
                    <a href="{{ $item['href'] }}" @click="open = false"
                       class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} flex items-center gap-3 {{ $item['active'] ? 'text-brand-blue bg-gray-50' : 'text-gray-700' }}">
                        <span class="inline-flex items-center">{!! $item['icon'] ?? '' !!}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach

                    {{-- Language buttons (mobile) --}}
                    <div class="flex gap-2 pt-3">
                        @php
                            $localeMeta = [
                                'am' => ['label' => 'አማ', 'title' => __('Amharic')],
                                'en' => ['label' => 'EN', 'title' => __('English')],
                            ];
                        @endphp

                        @foreach(['am', 'en'] as $locale)
                            <form method="POST" action="{{ route('locale.switch', $locale) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        @click="open = false"
                                        title="{{ $localeMeta[$locale]['title'] }}"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition
                                        {{ app()->getLocale() === $locale
                                            ? 'bg-brand-blue text-white shadow-sm'
                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-orange-600' }}">
                                    @if($locale === 'am')
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="2" y="5" width="20" height="14" rx="2" fill="currentColor" opacity=".12"></rect>
                                            <rect x="3" y="6" width="18" height="4" fill="#22c55e"></rect>
                                            <rect x="3" y="10" width="18" height="4" fill="#fbbf24"></rect>
                                            <rect x="3" y="14" width="18" height="4" fill="#ef4444"></rect>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 110-18 9 9 0 010 18z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.6 9h16.8M3.6 15h16.8" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c2.5 2.2 4 5.4 4 9s-1.5 6.8-4 9c-2.5-2.2-4-5.4-4-9s1.5-6.8 4-9z" />
                                        </svg>
                                    @endif
                                    <span>{{ $localeMeta[$locale]['label'] }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </template>
</nav>

<style>
    /* Focus: consistent, accessible */
    nav a:focus-visible,
    nav button:focus-visible {
        outline: 2px solid rgba(59, 130, 246, 0.35);
        outline-offset: 2px;
        border-radius: 14px;
    }

    /* Active indicator animation (kept, slightly smoother) */
    nav a.active-indicator div {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateX(-50%) scaleX(0); }
        to   { transform: translateX(-50%) scaleX(1); }
    }
</style>
