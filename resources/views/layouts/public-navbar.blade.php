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
    
    $localeMeta = [
        'am' => ['label' => __('public.language.short.am'), 'title' => __('public.language.full.am')],
        'en' => ['label' => __('public.language.short.en'), 'title' => __('public.language.full.en')],
    ];
@endphp

<nav x-data="{ open: false, scrolled: false }"
     x-init="scrolled = window.scrollY > 20; window.addEventListener('scroll', () => { scrolled = window.scrollY > 20; });"
     @click.away="open = false"
     :class="{
        'is-scrolled': scrolled
     }"
     class="sticky top-0 z-50 transition-all duration-300 public-navbar">

    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-4 sm:px-6 lg:px-10">
        <div class="flex flex-col gap-3">
            <div class="flex h-20 items-center justify-between px-4 rounded-2xl shadow-sm">

            {{-- BRAND --}}
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}"
                   class="group inline-flex items-center gap-3 rounded-xl px-2 py-1 transition">
                    @if($logo)
                        <img
                            src="{{ asset('storage/'.ltrim($logo, '/')) }}"
                            alt="{{ $brandName }}"
                            class="h-16 w-auto max-w-[220px] object-contain transition-all duration-300"
                            loading="eager"
                        >
                    @else
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-blue to-blue-600 text-white shadow-lg">
                            <x-heroicon-o-building-office-2 class="h-6 w-6" aria-hidden="true" />
                        </div>
                    @endif
                    <span class="text-lg font-semibold tracking-tight text-brand-blue">
                        {{ $brandName }}
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <div class="hidden lg:flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2 py-1 text-xs font-medium shadow-sm ring-1 ring-slate-200/50">
                    @foreach(['am', 'en'] as $locale)
                        <form method="POST" action="{{ route('locale.switch', $locale) }}" class="leading-none">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                            <button type="submit"
                                    title="{{ $localeMeta[$locale]['title'] }}"
                                    class="rounded-full px-2.5 py-1 text-[11px] font-semibold transition {{ app()->getLocale() === $locale ? 'bg-gradient-to-r from-brand-blue to-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                {{ strtoupper($localeMeta[$locale]['label']) }}
                            </button>
                        </form>
                        @if(!$loop->last)
                            <span class="text-slate-300">/</span>
                        @endif
                    @endforeach
                </div>

                <button type="button"
                        class="lg:hidden inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-2.5 text-gray-700 shadow-sm hover:bg-gray-50 hover:text-orange-600 transition relative z-50"
                        @click="open = !open"
                        :aria-expanded="open"
                        aria-label="{{ __('public.navigation.navigation_toggle') }}">
                    <x-heroicon-o-bars-3 x-show="!open" class="h-5 w-5" aria-hidden="true" />
                    <x-heroicon-o-x-mark x-show="open" x-cloak class="h-5 w-5" aria-hidden="true" />
                </button>
            </div>

        </div>

            {{-- DESKTOP NAV --}}
            <div class="hidden lg:flex lg:items-center lg:gap-1 lg:ml-6 lg:mr-4 lg:flex-nowrap">
                @php
                    $contactDropdown = [
                        [
                            'label' => __('public.navigation.contact_us') ?? 'Contact Us',
                            'href' => route('contact'),
                            'active' => request()->routeIs('contact'),
                        ],
                        [
                            'label' => __('public.navigation.contact_org') ?? 'Contact Org',
                            'href' => route('organization.contact'),
                            'active' => request()->routeIs('organization.contact'),
                        ],
                    ];

                    $navLinks = [
                        ['label' => __('public.navigation.downloads'), 'href' => route('downloads.index'), 'active' => request()->routeIs('downloads.*'), 'icon' => 'arrow-down-tray'],
                        ['label' => __('public.navigation.citizen_charter'), 'href' => route('citizen-charter.index'), 'active' => request()->routeIs('citizen-charter.*'), 'icon' => 'document-text'],
                        ['label' => __('public.navigation.partnerships'), 'href' => route('public.mous.index'), 'active' => request()->routeIs('public.mous.*'), 'icon' => 'hand-thumb-up'],
                    ];

                    if (Route::has('vacancies.index')) {
                        array_unshift($navLinks, [
                            'label' => __('public.navigation.vacancies'),
                            'href' => route('vacancies.index'),
                            'active' => request()->routeIs('vacancies.*'),
                            'icon' => 'briefcase',
                        ]);
                    }

                    $isContactActive = collect($contactDropdown)->contains('active', true);

                    $announcementDropdown = [];
                    if (Route::has('announcements.index')) {
                        $announcementDropdown[] = [
                            'label' => __('public.navigation.announcements'),
                            'href' => route('announcements.index'),
                            'active' => request()->routeIs('announcements.*'),
                            'icon' => 'speakerphone',
                        ];
                    }

                    $tenderRoute = null;
                    if (Route::has('tenders.index')) {
                        $tenderRoute = [
                            'label' => __('public.navigation.tenders'),
                            'href' => route('tenders.index'),
                            'active' => request()->routeIs('tenders.*'),
                            'icon' => 'document-arrow-down',
                        ];
                    } elseif (Route::has('admin.tenders.index') && auth()->check() && auth()->user()->can('view tenders')) {
                        $tenderRoute = [
                            'label' => __('public.navigation.tenders'),
                            'href' => route('admin.tenders.index'),
                            'active' => request()->routeIs('admin.tenders.*'),
                            'icon' => 'document-arrow-down',
                        ];
                    }

                    if ($tenderRoute) {
                        $announcementDropdown[] = $tenderRoute;
                    }

                    $hasAnnouncementDropdown = count($announcementDropdown) > 0;
                    $announcementActive = collect($announcementDropdown)->contains('active', true);
                @endphp

                {{-- Shared desktop link UI --}}
                @php
                    $desktopLinkBase = 'relative inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 desktop-link';
                    $desktopLinkHover = 'hover:bg-gray-50 hover:text-orange-600 hover:-translate-y-[1px]';
                @endphp

                {{-- Home (desktop) --}}
                <a href="{{ url('/') }}"
                   class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ request()->is('/') ? 'text-brand-blue' : 'text-gray-700' }}">
                    @if(request()->is('/'))
                        <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                    @endif
                    <span class="inline-flex items-center">
                        <x-heroicon-o-home class="h-4 w-4" aria-hidden="true" />
                    </span>
                    <span>{{ __('public.navigation.home') }}</span>
                </a>

                {{-- About dropdown (desktop) --}}
                @php $aboutActive = request()->routeIs('pages.*') || request()->routeIs('staff.*'); @endphp

                    <div class="relative group">
                        <button type="button"
                                aria-haspopup="true"
                                class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $aboutActive ? 'text-brand-blue' : 'text-gray-700' }} font-abyssinica">
                        <span class="inline-flex items-center gap-2">
                            <x-heroicon-o-information-circle class="h-4 w-4" aria-hidden="true" />
                            <span>{{ __('public.navigation.about') ?? 'About' }}</span>
                        </span>

                        <x-heroicon-o-chevron-down class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180 text-current" aria-hidden="true" />

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
                                        <x-heroicon-o-information-circle class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                    <span>{{ __('public.navigation.about') ?? 'About' }}</span>
                                </a>
                            @endif

                            @if(Route::has('staff.index'))
                                <a href="{{ route('staff.index') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('staff.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <x-heroicon-o-users class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                   <span>{{ __('public.navigation.staff') ?? 'Staff' }}</span>
                               </a>
                           @endif

                            @if(Route::has('public-servants.dashboard'))
                                <a href="{{ route('public-servants.dashboard') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('public-servants.dashboard') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <x-heroicon-o-users class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                    <span>{{ __('public.navigation.public_servant_dashboard') }}</span>
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
                            <x-heroicon-o-newspaper class="h-4 w-4" aria-hidden="true" />
                            <span>{{ __('public.navigation.news') }}</span>
                        </span>
                    </a>
                @endif

                {{-- Announcements dropdown (desktop) --}}
                @if($hasAnnouncementDropdown)
                    <div class="relative group">
                        <button type="button"
                                aria-haspopup="true"
                                class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $announcementActive ? 'text-brand-blue' : 'text-gray-700' }} font-abyssinica">
                            <span class="inline-flex items-center gap-2">
                                <x-heroicon-o-speakerphone class="h-4 w-4" aria-hidden="true" />
                                <span>{{ __('public.navigation.announcements') }}</span>
                            </span>
                            <x-heroicon-o-chevron-down class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180 text-current" aria-hidden="true" />
                            @if($announcementActive)
                                <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                            @endif
                        </button>

                        <div class="absolute left-0 mt-2 w-80 rounded-2xl bg-white border border-gray-100 shadow-xl z-50
                                opacity-0 translate-y-1 pointer-events-none
                                group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
                                group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto
                                transition-all duration-150">
                            <div class="p-2 font-abyssinica">
                                @foreach($announcementDropdown as $item)
                                    <a href="{{ $item['href'] }}"
                                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                              {{ $item['active'] ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                            <x-dynamic-component
                                                :component="'heroicon-o-'.$item['icon']"
                                                class="h-4 w-4"
                                                aria-hidden="true"
                                            />
                                        </span>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
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
                            <x-heroicon-o-briefcase class="h-4 w-4" aria-hidden="true" />
                            <span>{{ __('public.navigation.services') }}</span>
                        </span>
                        <x-heroicon-o-chevron-down class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180 text-current" aria-hidden="true" />
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
                                    <x-heroicon-o-rectangle-stack class="h-4 w-4" aria-hidden="true" />
                                </span>
                                <span>{{ __('public.navigation.services') }}</span>
                            </a>

                            @if(Route::has('service-requests.create'))
                                <a href="{{ route('service-requests.create') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('service-requests.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <x-heroicon-o-clipboard-document class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                    <span>{{ __('public.navigation.request_service') }}</span>
                                </a>
                            @endif

                            @if(Route::has('appointments.index'))
                                <a href="{{ route('appointments.index') }}"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                          {{ request()->routeIs('appointments.*') ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                        <x-heroicon-o-calendar-days class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                    <span>{{ __('public.navigation.appointments') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Remaining simple links --}}
                    @foreach($navLinks as $item)
                        <a href="{{ $item['href'] }}"
                           class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $item['active'] ? 'text-brand-blue' : 'text-gray-700' }}">
                        @if($item['active'])
                            <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                        @endif
                        <span class="inline-flex items-center gap-2">
                            <span class="inline-flex items-center text-current">
                                <x-dynamic-component
                                    :component="'heroicon-o-'.$item['icon']"
                                    class="h-4 w-4"
                                    aria-hidden="true"
                                />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </span>
                    </a>
                        @endforeach

                    {{-- Contact dropdown (desktop) --}}
                    <div class="relative group">
                        <button type="button"
                                aria-haspopup="true"
                                class="{{ $desktopLinkBase }} {{ $desktopLinkHover }} {{ $isContactActive ? 'text-brand-blue' : 'text-gray-700' }} font-abyssinica">
                            <span class="inline-flex items-center gap-2">
                                <x-heroicon-o-phone class="h-4 w-4" aria-hidden="true" />
                                <span>{{ __('public.navigation.contact') }}</span>
                            </span>
                            <x-heroicon-o-chevron-down class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180 text-current" aria-hidden="true" />
                            @if($isContactActive)
                                <div class="absolute -bottom-[9px] left-1/2 -translate-x-1/2 w-8 h-0.5 bg-brand-blue rounded-full"></div>
                            @endif
                        </button>

                        <div class="absolute left-0 mt-2 w-60 rounded-2xl bg-white border border-gray-100 shadow-xl z-50
                                opacity-0 translate-y-1 pointer-events-none
                                group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto
                                group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto
                                transition-all duration-150">
                            <div class="p-2 font-abyssinica">
                                @foreach($contactDropdown as $item)
                                    <a href="{{ $item['href'] }}"
                                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm hover:bg-gray-50 transition
                                              {{ $item['active'] ? 'text-brand-blue font-semibold' : 'text-gray-700' }}">
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 text-gray-700">
                                            <x-heroicon-o-phone class="h-4 w-4" aria-hidden="true" />
                                        </span>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open" x-cloak class="lg:hidden">
        <div class="fixed inset-0 z-30 bg-black/30 backdrop-blur-sm" @click="open = false"></div>
        <div x-show="open" x-transition x-cloak class="relative z-40 border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-2">

                    @php
                        $mobileLinkBase = 'block rounded-xl px-3 py-2.5 text-sm font-semibold transition mobile-link';
                        $mobileLinkHover = 'hover:bg-gray-50 hover:text-orange-600';
                    @endphp

                    {{-- Home (mobile) --}}
                    <a href="{{ url('/') }}" @click="open = false"
                       class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                        <span class="inline-flex items-center gap-3">
                            <x-heroicon-o-home class="h-4 w-4" aria-hidden="true" />
                            <span>{{ __('public.navigation.home') }}</span>
                        </span>
                    </a>

                    @php
                        $hasMobileAboutLinks = Route::has('pages.about') || Route::has('staff.index') || Route::has('public-servants.dashboard');
                    @endphp
                    @if($hasMobileAboutLinks)
                        <div x-data="{ openAbout: false }"
                             class="rounded-2xl border border-gray-100 bg-gray-50/60 p-3 font-abyssinica">
                            <button type="button"
                                    class="flex w-full items-center justify-between gap-2 px-1 pb-2 text-left text-sm font-semibold text-gray-900 transition hover:text-orange-600"
                                    @click="openAbout = !openAbout"
                                    :aria-expanded="openAbout.toString()">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-information-circle class="h-4 w-4" aria-hidden="true" />
                                    <span>{{ __('public.navigation.about') }}</span>
                                </div>
                                <span class="inline-flex h-4 w-4 text-current transition-transform duration-150"
                                      x-bind:class="openAbout ? 'rotate-180 text-orange-600' : ''">
                                    <x-heroicon-o-chevron-down aria-hidden="true" />
                                </span>
                            </button>

                            <div x-show="openAbout"
                                 x-transition
                                 x-cloak
                                 class="space-y-1 pt-1"
                                 @keydown.escape.window="openAbout = false">
                                @if(Route::has('pages.about'))
                                    <a href="{{ route('pages.about') }}" @click="open = false"
                                       class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                        <x-heroicon-o-information-circle class="h-4 w-4" aria-hidden="true" />
                                        <span>{{ __('public.navigation.about') }}</span>
                                    </a>
                                @endif

                                @if(Route::has('staff.index'))
                                    <a href="{{ route('staff.index') }}" @click="open = false"
                                       class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                        <x-heroicon-o-users class="h-4 w-4" aria-hidden="true" />
                                        <span>{{ __('public.navigation.staff') }}</span>
                                    </a>
                                @endif

                                @if(Route::has('public-servants.dashboard'))
                                    <a href="{{ route('public-servants.dashboard') }}" @click="open = false"
                                       class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                        <x-heroicon-o-users class="h-4 w-4" aria-hidden="true" />
                                        <span>{{ __('public.navigation.public_servant_dashboard') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- News (mobile) --}}
                    @if(Route::has('news.index'))
                        <a href="{{ route('news.index') }}" @click="open = false"
                           class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} text-gray-700">
                            <span class="inline-flex items-center gap-3">
                                <x-heroicon-o-newspaper class="h-4 w-4" aria-hidden="true" />
                                <span>{{ __('public.navigation.news') }}</span>
                            </span>
                        </a>
                    @endif

                    @if($hasAnnouncementDropdown)
                        <div x-data="{ openAnnouncements: false }"
                             class="rounded-2xl border border-gray-100 bg-gray-50/60 p-3 font-abyssinica">
                            <button type="button"
                                    class="flex w-full items-center justify-between gap-2 px-1 pb-2 text-left text-sm font-semibold text-gray-900 transition hover:text-orange-600"
                                    @click="openAnnouncements = !openAnnouncements"
                                    :aria-expanded="openAnnouncements.toString()">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-speakerphone class="h-4 w-4" aria-hidden="true" />
                                    <span>{{ __('public.navigation.announcements') }}</span>
                                </div>
                                <span class="inline-flex h-4 w-4 text-current transition-transform duration-150"
                                      x-bind:class="openAnnouncements ? 'rotate-180 text-orange-600' : ''">
                                    <x-heroicon-o-chevron-down aria-hidden="true" />
                                </span>
                            </button>

                            <div x-show="openAnnouncements"
                                 x-transition
                                 x-cloak
                                 class="space-y-1 pt-1"
                                 @keydown.escape.window="openAnnouncements = false">
                                @foreach($announcementDropdown as $item)
                                    <a href="{{ $item['href'] }}" @click="open = false"
                                       class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                        <x-dynamic-component
                                            :component="'heroicon-o-'.$item['icon']"
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        />
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Services (mobile) --}}
                    <div x-data="{ openServices: false }"
                         class="rounded-2xl border border-gray-100 bg-gray-50/60 p-3 font-abyssinica">
                        <button type="button"
                                class="flex w-full items-center justify-between gap-2 px-1 pb-2 text-left text-sm font-semibold text-gray-900 transition hover:text-orange-600"
                                @click="openServices = !openServices"
                                :aria-expanded="openServices.toString()">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-briefcase class="h-4 w-4" aria-hidden="true" />
                                <span>{{ __('public.navigation.services') }}</span>
                            </div>
                            <span class="inline-flex h-4 w-4 text-current transition-transform duration-150"
                                  x-bind:class="openServices ? 'rotate-180 text-orange-600' : ''">
                                <x-heroicon-o-chevron-down aria-hidden="true" />
                            </span>
                        </button>

                        <div x-show="openServices"
                             x-transition
                             x-cloak
                             class="space-y-1 pt-1"
                             @keydown.escape.window="openServices = false">
                            <a href="{{ url('/services') }}" @click="open = false"
                               class="block rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                {{ __('public.navigation.services') }}
                            </a>

                            @if(Route::has('service-requests.create'))
                                <a href="{{ route('service-requests.create') }}" @click="open = false"
                                   class="block rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                    {{ __('public.navigation.request_service') }}
                                </a>
                            @endif

                            @if(Route::has('appointments.index'))
                                <a href="{{ route('appointments.index') }}" @click="open = false"
                                   class="block rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition">
                                    {{ __('public.navigation.appointments') }}
                                </a>
                            @endif
                        </div>
                    </div>

                {{-- Remaining links --}}
                    @foreach($navLinks as $item)
                        <a href="{{ $item['href'] }}" @click="open = false"
                           class="{{ $mobileLinkBase }} {{ $mobileLinkHover }} flex items-center gap-3 {{ $item['active'] ? 'text-brand-blue bg-gray-50' : 'text-gray-700' }}">
                        <span class="inline-flex items-center">
                            <x-dynamic-component
                                :component="'heroicon-o-'.$item['icon']"
                                class="h-4 w-4"
                                aria-hidden="true"
                            />
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                    @endforeach

                    {{-- Contact dropdown (mobile) --}}
                    <div x-data="{ openContact: false }"
                         class="rounded-2xl border border-gray-100 bg-gray-50/60 p-3 font-abyssinica">
                        <button type="button"
                                class="flex w-full items-center justify-between gap-2 px-1 pb-2 text-left text-sm font-semibold text-gray-900 transition hover:text-orange-600"
                                @click="openContact = !openContact"
                                :aria-expanded="openContact.toString()">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-phone class="h-4 w-4" aria-hidden="true" />
                                <span>{{ __('public.navigation.contact') }}</span>
                            </div>
                            <span class="inline-flex h-4 w-4 text-current transition-transform duration-150"
                                  x-bind:class="openContact ? 'rotate-180 text-orange-600' : ''">
                                <x-heroicon-o-chevron-down aria-hidden="true" />
                            </span>
                        </button>

                        <div x-show="openContact"
                             x-transition
                             x-cloak
                             class="space-y-1 pt-1"
                             @keydown.escape.window="openContact = false">
                            @foreach($contactDropdown as $item)
                                <a href="{{ $item['href'] }}" @click="open = false"
                                   class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:text-orange-600 transition {{ $item['active'] ? 'text-brand-blue' : '' }}">
                                    <x-heroicon-o-phone class="h-4 w-4" aria-hidden="true" />
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Language buttons (mobile) --}}
                    <div class="flex gap-2 pt-3">
                        @foreach(['am', 'en'] as $locale)
                            <form method="POST" action="{{ route('locale.switch', $locale) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                                <button type="submit"
                                        @click="open = false"
                                        title="{{ $localeMeta[$locale]['title'] }}"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold transition
                                        {{ app()->getLocale() === $locale
                                            ? 'bg-brand-blue text-white shadow-sm'
                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-orange-600' }}">
                                    <x-heroicon-o-flag class="h-4 w-4" aria-hidden="true" />
                                    <span>{{ $localeMeta[$locale]['label'] }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .public-navbar {
        background-color: transparent;
    }

    .public-navbar.is-scrolled {
        background-color: #ffffff;
        box-shadow: 0 10px 25px -15px rgba(15, 23, 42, 0.45);
        border-bottom: 1px solid #e5e7eb;
    }

    /* Focus: consistent, accessible */
    nav a:focus-visible,
    nav button:focus-visible {
        outline: 2px solid rgba(59, 130, 246, 0.35);
        outline-offset: 2px;
        border-radius: 14px;
    }

    .public-navbar .desktop-link,
    .public-navbar .mobile-link {
        font-size: 0.95rem;
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.querySelector('.public-navbar');
        if (!navbar) {
            return;
        }

        const updateNavbar = () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 20);
        };

        updateNavbar();
        window.addEventListener('scroll', updateNavbar, { passive: true });
    });
</script>
