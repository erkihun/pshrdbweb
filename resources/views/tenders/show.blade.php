@extends('layouts.public')

@section('content')
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-5xl space-y-6 px-4">
   

            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-3">
                <div class="flex flex-col gap-2">
                    <p class="text-xs uppercase tracking-wider text-slate-400">Tender</p>
                    <h1 class="text-3xl font-semibold text-slate-900">{{ $tender->title }}</h1>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                    <span>Tender #: {{ $tender->tender_number ?? '—' }}</span>
                    <span>Published: {{ optional($tender->published_at)->format('M d, Y') ?? 'TBA' }}</span>
                    <span>Closing: {{ optional($tender->closing_date)->format('M d, Y') ?? 'Open until further notice' }}</span>
                    <span>Views: {{ $tender->view_count ?? 0 }}</span>
                </div>
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border px-4 py-1 text-xs font-semibold uppercase tracking-wider {{ $tender->isClosed() ? 'border-rose-200 bg-rose-50 text-rose-600' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                        {{ $tender->isClosed() ? 'Closed' : 'Open' }}
                    </span>
                </div>
            </div>
            <div class="prose prose-slate mt-8 max-w-none">
                {!! $tender->description !!}
            </div>
            @if($tender->attachment_path)
                <a
                    href="{{ asset('storage/' . ltrim($tender->attachment_path, '/')) }}"
                    target="_blank"
                    class="mt-6 inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Download attachment
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
