@php
    $adminUser = auth()->user();
    $applicantUser = auth('applicant')->user();
    $isApplicantArea = request()->routeIs('applicant.*');
    $isApplicantAuthenticated = $applicantUser !== null;
    $isVacancyFlow = request()->routeIs('vacancies.index')
        || request()->routeIs('vacancies.show')
        || request()->routeIs('vacancies.apply*');
    $isTrack = request()->routeIs('vacancies.track*');
    $showApplicantNav = $isApplicantArea || ($adminUser === null && ($isVacancyFlow || $isTrack));

    $linkBase = 'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium transition';
    $linkIdle = 'text-gray-600 hover:text-blue-600 hover:bg-blue-50';
    $linkActive = 'bg-blue-600 text-white';
    $sidebarLink = 'flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold transition';
    $sidebarIdle = 'text-slate-600 hover:bg-slate-50 hover:text-slate-900';
    $sidebarActive = 'bg-blue-600 text-white';
    $isDashboard = request()->routeIs('applicant.dashboard');
    $isProfile = request()->routeIs('applicant.profile');
    $isApplications = request()->routeIs('applicant.applications.*');
@endphp

@if($showApplicantNav)
    @if($isApplicantArea)
        <div class="sticky top-24 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">{{ __('vacancies.public.dashboard_label') }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $applicantUser?->email ?? __('common.labels.unknown') }}</p>
            </div>

            <nav aria-label="Applicant navigation" class="space-y-2 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                <a
                    href="{{ route('applicant.dashboard') }}"
                    class="{{ $sidebarLink }} {{ $isDashboard ? $sidebarActive : $sidebarIdle }}"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('common.nav.dashboard') }}
                </a>
                <a
                    href="{{ route('applicant.profile') }}"
                    class="{{ $sidebarLink }} {{ $isProfile ? $sidebarActive : $sidebarIdle }}"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('common.nav.profile') }}
                </a>
                <a
                    href="{{ route('applicant.dashboard') }}"
                    class="{{ $sidebarLink }} {{ $isApplications ? $sidebarActive : $sidebarIdle }}"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('vacancies.public.applications_nav') }}
                </a>
                <form method="POST" action="{{ route('applicant.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="{{ $sidebarLink }} text-slate-600 hover:bg-rose-50 hover:text-rose-600 w-full"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('common.nav.logout') }}
                    </button>
                </form>
            </nav>
        </div>
    @else
        <div class="sticky top-24 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">{{ __('vacancies.public.applicant_portal') }}</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $isApplicantAuthenticated ? $applicantUser->email : __('vacancies.public.apply_label') }}
                </p>
            </div>

            <nav aria-label="Applicant navigation" class="space-y-2 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                @if(! $isApplicantAuthenticated)
                    <a
                        href="{{ route('vacancies.index') }}#vacancy-list"
                        class="{{ $sidebarLink }} {{ $isVacancyFlow ? $sidebarActive : $sidebarIdle }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('vacancies.public.apply_label') }}
                    </a>
                    <a
                        href="{{ route('vacancies.track') }}"
                        class="{{ $sidebarLink }} {{ $isTrack ? $sidebarActive : $sidebarIdle }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ __('vacancies.public.track_nav') }}
                    </a>
                    <a
                        href="{{ route('applicant.login') }}"
                        class="{{ $sidebarLink }} {{ $sidebarIdle }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        {{ __('common.nav.login') }}
                    </a>
                @else
                    <a
                        href="{{ route('applicant.dashboard') }}"
                        class="{{ $sidebarLink }} {{ $sidebarIdle }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __('common.nav.dashboard') }}
                    </a>
                    <a
                        href="{{ route('applicant.profile') }}"
                        class="{{ $sidebarLink }} {{ $sidebarIdle }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('common.nav.profile') }}
                    </a>
                    <a
                        href="{{ route('applicant.dashboard') }}"
                        class="{{ $sidebarLink }} {{ $sidebarIdle }}"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('vacancies.public.applications_nav') }}
                    </a>
                    <form method="POST" action="{{ route('applicant.logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="{{ $sidebarLink }} text-slate-600 hover:bg-rose-50 hover:text-rose-600 w-full"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('common.nav.logout') }}
                        </button>
                    </form>
                @endif
            </nav>
        </div>
    @endif
@endif
