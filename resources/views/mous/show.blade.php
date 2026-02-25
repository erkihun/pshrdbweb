@extends('layouts.public')

@section('content')
    @php
        $locale = app()->getLocale();
        $title = $locale === 'am'
            ? ($mou->title_am ?: $mou->title_en)
            : ($mou->title_en ?: $mou->title_am);
        $scope = $locale === 'am'
            ? ($mou->scope_am ?: $mou->scope_en)
            : ($mou->scope_en ?: $mou->scope_am);
        $keyAreas = $locale === 'am'
            ? ($mou->key_areas_am ?: $mou->key_areas_en)
            : ($mou->key_areas_en ?: $mou->key_areas_am);
        $signed = $mou->signed_at?->format('F j, Y') ?? '—';
        $effectiveFrom = $mou->effective_from?->format('F j, Y') ?? '—';
        $effectiveTo = $mou->effective_to?->format('F j, Y') ?? '—';
    @endphp

    <div class="mx-auto flex max-w-5xl flex-col gap-8 pb-16 pt-6">
        <section class="rounded-3xl bg-white p-6 shadow-lg">
            <header class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-slate-400">
                        {{ __('public.navigation.partnerships') }}
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $title }}</h1>
                    <p class="text-sm text-slate-500">{{ $mou->partner->display_name }}</p>
                </div>
                <div class="flex items-center gap-3">
                        @if($mou->partner->logo_path)
                            <img
                                src="{{ asset('storage/' . $mou->partner->logo_path) }}"
                                alt="{{ $mou->partner->display_name }} logo"
                                class="h-16 w-16 rounded-2xl object-cover"
                                loading="lazy"
                            >
                    @else
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                            <x-heroicon-o-briefcase class="h-8 w-8" aria-hidden="true" />
                        </div>
                    @endif
                </div>
            </header>

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase  text-slate-400">
                        {{ __('mous.public.show.fields.metadata') }}
                    </p>
                    <dl class="mt-4 space-y-3 text-sm text-slate-700">
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500">{{ __('mous.public.show.fields.reference') }}</dt>
                            <dd>{{ $mou->reference_no ?? '—' }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500">{{ __('mous.public.show.fields.signed') }}</dt>
                            <dd>{{ $signed }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500">{{ __('mous.public.show.fields.effective_period') }}</dt>
                            <dd>{{ $effectiveFrom }} – {{ $effectiveTo }}</dd>
                        </div>
                    </dl>
                    <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase  text-slate-700">
                        <span class="h-2.5 w-2.5 rounded-full {{ $mou->status === 'active' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                        {{ __('mous.statuses.' . $mou->status) }}
                    </div>
                </article>

                <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase  text-slate-400">
                        {{ __('mous.public.show.fields.attachment') }}
                    </p>
                    <div class="mt-4 text-sm text-slate-700">
                        @if($mou->attachment_path)
                            <a
                                href="{{ asset('storage/' . $mou->attachment_path) }}"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm font-semibold text-brand-blue transition hover:border-brand-blue hover:bg-brand-blue/5"
                                target="_blank"
                                rel="noopener"
                            >
                                <x-heroicon-o-arrow-down-tray class="h-4 w-4" aria-hidden="true" />
                                {{ __('mous.public.show.fields.download') }}
                            </a>
                        @else
                            <p class="text-slate-500">{{ __('mous.public.show.fields.no_attachment') }}</p>
                        @endif
                    </div>
                </article>
            </div>

            <div class="mt-8 grid gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase  text-slate-400">
                        {{ __('mous.public.show.fields.scope') }}
                    </p>
                    <div class="prose max-w-none mt-3 text-sm text-slate-700">
                        {!! $scope ?: '<p class="text-slate-500">—</p>' !!}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase  text-slate-400">
                        {{ __('mous.public.show.fields.key_areas') }}
                    </p>
                    <div class="prose max-w-none mt-3 text-sm text-slate-700">
                        {!! $keyAreas ?: '<p class="text-slate-500">—</p>' !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
