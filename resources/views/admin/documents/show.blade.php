@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $document->display_title }}</h1>
                <p class="text-sm text-slate-500">{{ $document->slug }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.documents.edit', $document) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300"
                >
                    Edit
                </a>
                <a
                    href="{{ route('admin.documents.index') }}"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.back') }}
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.category') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $document->category?->display_name ?? __('common.labels.category') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $document->is_published ? __('common.status.published') : __('common.status.draft') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.type') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ strtoupper($document->file_type) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Size</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ number_format($document->file_size / 1024, 1) }} KB
                    </dd>
                </div>
            </dl>

            @if ($document->display_description)
                <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.description') }}</h2>
                    <p class="mt-2 whitespace-pre-line">{{ $document->display_description }}</p>
                </div>
            @endif

            <div class="mt-6 border-t border-slate-100 pt-6">
                <a
                    href="{{ asset('storage/' . $document->file_path) }}"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                    download
                >
                    Download File
                </a>
            </div>
        </div>
    </div>
@endsection

