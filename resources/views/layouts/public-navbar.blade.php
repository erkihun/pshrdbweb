@php
    $siteSettings = $site_settings ?? [];
    $branding = $siteSettings['site.branding'] ?? [];
    $brandName = $branding['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel');
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-brand-border shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="text-lg font-semibold  text-brand-ink">
                {{ $brandName }}
            </a>
        </div>

        <div class="hidden lg:flex lg:items-center lg:gap-6">
            <a href="{{ url('/') }}" class=" font-semibold uppercase  text-brand-muted hover:text-brand-blue">Home</a>
            <a href="{{ route('services.index') }}" class=" font-semibold uppercase  text-brand-muted hover:text-brand-blue">{{ __('common.nav.services') }}</a>
            <a href="{{ route('news.index') }}" class=" font-semibold uppercase  text-brand-muted hover:text-brand-blue">{{ __('common.nav.news') }}</a>
            <a href="{{ route('announcements.index') }}" class=" font-semibold uppercase  text-brand-muted hover:text-brand-blue">{{ __('common.nav.announcements') }}</a>
            <a href="{{ route('downloads.index') }}" class=" font-semibold uppercase  text-brand-muted hover:text-brand-blue">{{ __('common.nav.downloads') }}</a>
            <a href="{{ route('contact.create') }}" class=" font-semibold uppercase  text-brand-muted hover:text-brand-blue">{{ __('common.nav.contact') }}</a>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase  text-brand-muted">
                <form method="POST" action="{{ route('locale.switch', 'am') }}">
                    @csrf
                    <button type="submit" class="{{ app()->getLocale() === 'am' ? 'text-brand-blue font-bold' : 'text-brand-muted hover:text-brand-blue' }}">AM</button>
                </form>
                <span class="text-brand-border">/</span>
                <form method="POST" action="{{ route('locale.switch', 'en') }}">
                    @csrf
                    <button type="submit" class="{{ app()->getLocale() === 'en' ? 'text-brand-blue font-bold' : 'text-brand-muted hover:text-brand-blue' }}">EN</button>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-3 lg:hidden">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-2xl border border-brand-border bg-white px-3 py-2 text-brand-muted shadow-sm hover:border-brand-blue hover:text-brand-blue focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                @click="open = !open"
                :aria-expanded="open"
            >
                <span class="sr-only">Toggle navigation</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div class="lg:hidden" x-show="open" x-transition>
        <div class="space-y-2 border-t border-brand-border bg-white px-4 py-3">
            <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2  font-semibold uppercase  text-brand-muted hover:bg-brand-bg hover:text-brand-blue">Home</a>
            <a href="{{ route('services.index') }}" class="block rounded-lg px-3 py-2  font-semibold uppercase  text-brand-muted hover:bg-brand-bg hover:text-brand-blue">{{ __('common.nav.services') }}</a>
            <a href="{{ route('news.index') }}" class="block rounded-lg px-3 py-2  font-semibold uppercase  text-brand-muted hover:bg-brand-bg hover:text-brand-blue">{{ __('common.nav.news') }}</a>
            <a href="{{ route('announcements.index') }}" class="block rounded-lg px-3 py-2  font-semibold uppercase  text-brand-muted hover:bg-brand-bg hover:text-brand-blue">{{ __('common.nav.announcements') }}</a>
            <a href="{{ route('downloads.index') }}" class="block rounded-lg px-3 py-2  font-semibold uppercase  text-brand-muted hover:bg-brand-bg hover:text-brand-blue">{{ __('common.nav.downloads') }}</a>
            <a href="{{ route('contact.create') }}" class="block rounded-lg px-3 py-2  font-semibold uppercase  text-brand-muted hover:bg-brand-bg hover:text-brand-blue">{{ __('common.nav.contact') }}</a>
            <div class="flex items-center gap-2 pt-2 text-xs font-semibold uppercase  text-brand-muted">
                <form method="POST" action="{{ route('locale.switch', 'am') }}">
                    @csrf
                    <button type="submit" class="{{ app()->getLocale() === 'am' ? 'text-brand-blue font-bold' : 'text-brand-muted hover:text-brand-blue' }}">AM</button>
                </form>
                <span class="text-brand-border">/</span>
                <form method="POST" action="{{ route('locale.switch', 'en') }}">
                    @csrf
                    <button type="submit" class="{{ app()->getLocale() === 'en' ? 'text-brand-blue font-bold' : 'text-brand-muted hover:text-brand-blue' }}">EN</button>
                </form>
            </div>
        </div>
    </div>
</nav>
