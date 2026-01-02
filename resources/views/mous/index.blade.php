@extends('layouts.public')

@section('content')
    <div class="mx-auto flex max-w-7xl flex-col gap-6 pb-16 pt-6">
        <header class="rounded-3xl bg-white p-6 shadow-lg">
          
            <div class="mt-3 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h4 class="text-2xl font-semibold text-slate-900">{{ __('mous.public.title') }}</h4>
              
                </div>
                <div class="hidden text-sm text-slate-500 md:flex">
                    {{ $mous->total() }} {{ __('mous.public.stats.total') }}
                </div>
            </div>
        </header>

        <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <form class="grid gap-4 lg:grid-cols-4" method="GET">
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="partner">{{ __('mous.public.filters.partner') }}</label>
                    <select id="partner" name="partner" class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900">
                        <option value="">{{ __('mous.public.filters.any_partner') }}</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" @selected((string) request('partner') === (string) $partner->id)>{{ $partner->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="status">{{ __('mous.public.filters.status') }}</label>
                    <select id="status" name="status" class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900">
                        <option value="">{{ __('mous.public.filters.any_status') }}</option>
                        <option value="active" @selected(request('status') === 'active')>{{ __('mous.statuses.active') }}</option>
                        <option value="expired" @selected(request('status') === 'expired')>{{ __('mous.statuses.expired') }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="year">{{ __('mous.public.filters.year') }}</label>
                    <select id="year" name="year" class="mt-1 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900">
                        <option value="">{{ __('mous.public.filters.any_year') }}</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" @selected((string) request('year') === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        {{ __('common.actions.filter') }}
                    </button>
                </div>
            </form>
        </section>

        @php
            $branding = $site_settings['site.branding'] ?? [];
            $ourLogoPath = $branding['logo_path'] ?? null;
        @endphp

        <section class="grid gap-6 md:grid-cols-2">
            @forelse($mous as $mou)
                @php
                    $locale = app()->getLocale();
                    $title = $locale === 'am'
                        ? ($mou->title_am ?: $mou->title_en)
                        : ($mou->title_en ?: $mou->title_am);
                    $signed = $mou->signed_at?->format('F j, Y') ?? '—';
                    $statusClass = $mou->status === 'active'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-orange-100 text-amber-700';
                @endphp
                <article class="group rounded-3xl border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-slate-200 hover:shadow-lg">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase  text-slate-400">
                            {{ $mou->partner->display_name }}
                        </p>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ __('mous.statuses.' . $mou->status) }}
                        </span>
                    </div>
                    <h2 class="mt-4 text-lg font-semibold text-slate-900">{{ $title }}</h2>
                    <div class="mt-5 grid gap-4 border-y border-slate-100 py-5 sm:grid-cols-2 sm:items-center">
                        <div class="flex items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            @if($ourLogoPath)
                                <img
                                    src="{{ asset('storage/' . ltrim($ourLogoPath, '/')) }}"
                                    alt="{{ $branding['site_name_en'] ?? 'Logo' }}"
                                    class="h-12 w-auto object-contain"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8M5 21V5a1 1 0 011-1h12a1 1 0 011 1v16M9 21V9h6v12M9 3h6M7 7h2M15 7h2M7 11h2M15 11h2" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            @if($mou->partner->logo_path)
                                <img
                                    src="{{ asset('storage/' . $mou->partner->logo_path) }}"
                                    alt="{{ $mou->partner->display_name }} logo"
                                    class="h-12 w-auto object-contain"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                    <x-heroicon-o-briefcase class="h-6 w-6" aria-hidden="true" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm text-slate-500">
                        <span>{{ __('mous.public.card.signed') }} {{ $signed }}</span>
                        <span>{{ __('mous.public.card.status') }} {{ __('mous.statuses.' . $mou->status) }}</span>
                    </div>
                    <div class="mt-5 flex items-center justify-end border-t border-slate-100 pt-4">
                        <a
                            href="{{ route('public.mous.show', ['identifier' => $mou->public_slug]) }}"
                            class="rounded-full text-sm font-semibold text-brand-blue transition hover:text-brand-blue-dark"
                        >
                            {{ __('mous.public.card.view_details') }}
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                    {{ __('mous.public.empty') }}
                </div>
            @endforelse
        </section>

        @if($mous->hasPages())
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                {{ $mous->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
