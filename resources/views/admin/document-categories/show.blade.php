@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ $category->name }}</h1>
            <p class="text-sm text-slate-500">{{ $category->slug }}</p>
            @if($category->name_am && $category->name_am !== $category->name)
                <p class="text-sm text-slate-500">{{ $category->name_am }}</p>
            @endif
        </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.document-categories.edit', $category) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300"
                >
                    Edit
                </a>
                <a
                    href="{{ route('admin.document-categories.index') }}"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.back') }}
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $category->is_active ? __('common.status.active') : __('common.status.inactive') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.sort_order') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $category->sort_order }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
@endsection

