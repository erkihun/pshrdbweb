@extends('layouts.public')

@php use Illuminate\Support\Str; @endphp

@section('content')
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-6xl px-4 space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-600">Announcements</p>
                <h1 class="text-3xl font-semibold text-slate-900">Tenders</h1>
                <p class="text-sm text-slate-500">Browse the latest open tenders and download the details you need.</p>
            </div>
            <p class="text-sm text-slate-500">{{ $tenders->total() }} tenders available</p>
        </div>

        <form action="{{ route('tenders.index') }}" method="GET" class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
            <input
                type="search"
                name="q"
                value="{{ $filters['q'] ?? '' }}"
                placeholder="Search by title, keywords..."
                class="col-span-2 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue"
            >
            <select
                name="status"
                class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue"
            >
                <option value="">All statuses</option>
                <option value="open" @selected(($filters['status'] ?? '') === 'open')>Open</option>
                <option value="closed" @selected(($filters['status'] ?? '') === 'closed')>Closed</option>
            </select>
            <button
                type="submit"
                class="rounded-lg bg-brand-blue px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-blue-dark focus:outline-none focus:ring-2 focus:ring-brand-blue"
            >
                Apply
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
                            <p class="text-xs uppercase tracking-wider text-slate-400">Tender number: {{ $tender->tender_number ?? '—' }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full border px-3 py-1 font-semibold uppercase tracking-wider {{ $tender->isClosed() ? 'border-rose-200 bg-rose-50 text-rose-600' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                                {{ $tender->isClosed() ? 'Closed' : 'Open' }}
                            </span>
                            <span class="text-slate-500">Published: {{ optional($tender->published_at)->format('M d, Y') ?? 'TBA' }}</span>
                            <span class="text-slate-500">Closing: {{ optional($tender->closing_date)->format('M d, Y') ?? 'No deadline' }}</span>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-slate-600">{{ Str::limit(strip_tags($tender->description ?? ''), 220) }}</p>
                    @php
                        $tenderRoute = route('tenders.show', ['tender' => $tender->slug ?? $tender->id]);
                    @endphp
                    <div class="mt-6 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ $tender->view_count ?? 0 }} views</span>
                        <a href="{{ $tenderRoute }}" class="font-semibold text-brand-blue hover:text-brand-blue-dark">View details &rarr;</a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 p-8 text-center text-sm text-slate-500">
                    <p>No tenders match your search yet.</p>
                </div>
            @endforelse
        </div>

        <div>
            {{ $tenders->withQueryString()->links() }}
        </div>
    </div>
</section>
@endsection
