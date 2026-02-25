@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $display->title_en ?: 'Signage Display' }}</h1>
                <p class="text-sm text-slate-500">Slug: {{ $display->slug }}</p>
            </div>
            <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.signage.displays.edit', $display) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    Edit
                </a>
                <a href="{{ route('admin.signage.displays.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    Back
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="grid grid-cols-1 gap-4 px-6 py-6 lg:grid-cols-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Template</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $display->template->name_en ?: $display->template->name_am ?: $display->template->slug }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Refresh</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $display->refresh_seconds }} seconds</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Published at</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $display->published_at?->format('d M Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Status</p>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $display->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                        {{ $display->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Payload</p>
                    <pre class="mt-2 overflow-auto rounded-lg border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700">{{ json_encode($display->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </div>
    </div>
@endsection
