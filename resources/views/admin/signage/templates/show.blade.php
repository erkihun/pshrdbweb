@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $template->name_en ?: 'Signage Template' }}</h1>
                <p class="text-sm text-slate-500">Slug: {{ $template->slug }}</p>
            </div>
            <div class="inline-flex items-center gap-3">
                <a href="{{ route('admin.signage.templates.edit', ['template' => $template->slug]) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    Edit
                </a>
                <a href="{{ route('admin.signage.templates.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    Back
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="grid grid-cols-1 gap-4 px-6 py-6 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Orientation</p>
                    <p class="text-sm font-semibold text-slate-900">{{ ucfirst($template->orientation) }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Layout</p>
                    <p class="text-sm font-semibold text-slate-900">{{ \Illuminate\Support\Str::of($template->layout)->replace('_', ' ')->title() }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Created</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $template->created_at?->format('d M Y H:i') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Status</p>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $template->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            @if($template->schema)
                <div class="border-t border-slate-100 px-6 py-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Schema</p>
                    <pre class="mt-2 overflow-auto rounded-lg border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700">{{ json_encode($template->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Displays ({{ $template->displays->count() }})</h2>
                    <a href="{{ route('admin.signage.displays.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                        View all displays
                    </a>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($template->displays as $display)
                    <div class="px-6 py-4">
                        <a href="{{ route('admin.signage.displays.show', $display) }}" class="text-sm font-semibold text-slate-900 hover:text-blue-600">
                            {{ $display->title_en ?: $display->title_am ?: 'Untitled Display' }}
                        </a>
                        <p class="text-xs text-slate-500">{{ $display->slug }} · {{ $display->is_published ? 'Published' : 'Draft' }}</p>
                    </div>
                @empty
                    <div class="px-6 py-4 text-sm text-slate-500">
                        No displays are using this template yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
