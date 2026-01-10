@extends('layouts.public')

@php use Illuminate\Support\Str; @endphp

@section('content')
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-6xl px-4 space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-600">{{ __('public.tenders.label') }}</p>
                <h1 class="text-3xl font-semibold text-slate-900">{{ __('public.tenders.title') }}</h1>
                <p class="text-sm text-slate-500">{{ __('public.tenders.description') }}</p>
            </div>
            <p class="text-sm text-slate-500">{{ __('public.tenders.available', ['count' => $tenders->total()]) }}</p>
        </div>

        <form action="{{ route('tenders.index') }}" method="GET" class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
            <input
                type="search"
                name="q"
                value="{{ $filters['q'] ?? '' }}"
                placeholder="{{ __('public.tenders.search_placeholder') }}"
                class="col-span-2 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue"
            >
            <select
                name="status"
                class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue"
            >
                <option value="">{{ __('public.tenders.status_all') }}</option>
                <option value="open" @selected(($filters['status'] ?? '') === 'open')>{{ __('public.tenders.status_open') }}</option>
                <option value="closed" @selected(($filters['status'] ?? '') === 'closed')>{{ __('public.tenders.status_closed') }}</option>
            </select>
            <button
                type="submit"
                class="rounded-lg bg-brand-blue px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-blue-dark focus:outline-none focus:ring-2 focus:ring-brand-blue"
            >
                {{ __('public.tenders.button_apply') }}
            </button>
        </form>

        <div class="space-y-6">
            @forelse($tenders as $tender)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-lg">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            @php
                                $tenderRoute = route('tenders.show', ['tender' => $tender->slug ?? $tender->id]);
                            @endphp
                            <h2 class="text-xl font-semibold text-slate-900">
                                <a href="{{ $tenderRoute }}" class="hover:text-brand-blue">{{ $tender->title }}</a>
                            </h2>
                            <p class="text-xs uppercase tracking-wider text-slate-400">
                                {{ __('public.tenders.tender_number', ['number' => $tender->tender_number ?? __('public.tenders.tender_number_missing')]) }}
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full border px-3 py-1 font-semibold uppercase tracking-wider {{ $tender->isClosed() ? 'border-rose-200 bg-rose-50 text-rose-600' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                                {{ $tender->isClosed() ? __('public.tenders.status_closed') : __('public.tenders.status_open') }}
                            </span>
                            <span class="text-slate-500">
                                {{ __('public.tenders.published_label', ['date' => optional($tender->published_at)->format('M d, Y') ?? __('public.tenders.published_missing')]) }}
                            </span>
                            <span class="text-slate-500">
                                {{ __('public.tenders.closing_label', ['date' => optional($tender->closing_date)->format('M d, Y') ?? __('public.tenders.closing_none')]) }}
                            </span>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-600">{{ Str::limit(strip_tags($tender->description ?? ''), 220) }}</p>
                    @php
                        $tenderRoute = route('tenders.show', ['tender' => $tender->slug ?? $tender->id]);
                    @endphp
                    <div class="mt-6 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ __('public.tenders.views', ['count' => $tender->view_count ?? 0]) }}</span>
                        <a href="{{ $tenderRoute }}" class="font-semibold text-brand-blue hover:text-brand-blue-dark">
                            {{ __('public.tenders.view_details') }} &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 p-8 text-center text-sm text-slate-500">
                    <p>{{ __('public.tenders.no_results') }}</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $tenders->withQueryString()->links() }}
        </div>
    </div>
</section>
@endsection
