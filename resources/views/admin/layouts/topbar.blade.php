@php
    $locale = app()->getLocale();
    $role = Auth::user()->getRoleNames()->first();
@endphp

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 shadow-sm backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <button
            type="button"
            class="inline-flex items-center justify-center rounded-2xl border border-transparent bg-white px-3 py-2 text-slate-500 shadow-sm hover:border-slate-200 hover:text-slate-900 lg:hidden"
            @click="sidebarOpen = true"
            aria-label="Open sidebar"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="flex-1">
            <h1 class="text-lg font-semibold text-slate-900">
                @if(isset($title))
                    {{ $title }}
                @else
                    @yield('title', __('ui.dashboard'))
                @endif
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative">
                <button
                    type="button"
                    data-dropdown-trigger="notifications"
                    aria-expanded="false"
                    class="relative flex h-10 w-10 items-center justify-center rounded-full border border-slate-100 bg-white shadow transition hover:border-slate-200 hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-blue"
                >
                    <span class="sr-only">{{ __('ui.notifications') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span aria-hidden="true" class="pointer-events-none absolute top-1 right-0 h-2.5 w-2.5 rounded-full bg-brand-gold ring-2 ring-white"></span>
                </button>
                <div
                    data-dropdown-panel="notifications"
                    role="menu"
                    aria-hidden="true"
                    class="hidden absolute right-0 z-20 mt-3 w-64 rounded-[1.75rem] border border-slate-200 bg-white p-4 shadow-lg"
                >
                    <p class="text-[10px] text-slate-400">{{ __('ui.notifications') }}</p>
                    <div class="mt-3 space-y-3 text-[13px] text-slate-700">
                        <p class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3  text-slate-600">New ticket received: #{{ \Illuminate\Support\Str::random(6) }}</p>
                        <p class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3  text-slate-600">Service request assigned to {{ Auth::user()->name }}</p>
                        <p class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3  text-slate-600">Document uploads scheduled for review</p>
                    </div>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-1 text-[11px] font-semibold text-slate-600 shadow-sm">
                <form method="POST" action="{{ route('locale.switch', 'am') }}">
                    @csrf
                    <button type="submit" class="rounded-full px-2 py-0.5 transition {{ $locale === 'am' ? 'bg-brand-blue/10 text-brand-blue font-bold' : 'text-slate-500 hover:text-slate-900' }}">{{ __('ui.am') }}</button>
                </form>
                <span class="text-slate-300">/</span>
                <form method="POST" action="{{ route('locale.switch', 'en') }}">
                    @csrf
                    <button type="submit" class="rounded-full px-2 py-0.5 transition {{ $locale === 'en' ? 'bg-brand-blue/10 text-brand-blue font-bold' : 'text-slate-500 hover:text-slate-900' }}">{{ __('ui.en') }}</button>
                </form>
            </div>

            <div class="relative">
                <button
                    type="button"
                    data-dropdown-trigger="profile"
                    aria-expanded="false"
                    class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-3 py-2 shadow-sm transition hover:border-slate-300 hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-blue"
                >
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-blue  font-semibold uppercase text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-left leading-tight">
                        <p class="text-[10px] text-slate-400">{{ __('ui.profile') }}</p>
                        <p class=" font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div
                    data-dropdown-panel="profile"
                    role="menu"
                    aria-hidden="true"
                    class="hidden absolute right-0 z-20 mt-4 w-64 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-lg"
                >
                    <p class="text-[10px] text-slate-400">{{ __('ui.profile') }}</p>
                    <a href="{{ route('profile.edit') }}" class="mt-2 block rounded-xl px-3 py-2  font-semibold text-slate-600 hover:bg-slate-50">{{ __('ui.profile') }}</a>
                    @if($role)
                        <p class="mt-1 text-[10px] text-slate-500">{{ $role }}</p>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mt-1 w-full rounded-xl px-3 py-2 text-left  font-semibold text-slate-600 hover:bg-slate-50">{{ __('ui.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
