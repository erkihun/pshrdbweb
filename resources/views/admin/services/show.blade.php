@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $service->display_title }}</h1>
                <p class="text-sm text-slate-500">{{ $service->slug }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.services.edit', $service) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300"
                >
                    Edit
                </a>
                <a
                    href="{{ route('admin.services.index') }}"
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
                        {{ $service->is_active ? __('common.status.active') : __('common.status.inactive') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.sort_order') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $service->sort_order }}
                    </dd>
                </div>
            </dl>

            <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.description') }}</h2>
                <x-rich-content class="mt-2 text-sm text-slate-700">
                    {!! $service->display_description !!}
                </x-rich-content>
            </div>

            @if ($service->display_requirements)
                <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.requirements') }}</h2>
                    <x-rich-content class="mt-2 text-sm text-slate-700">
                        {!! $service->display_requirements !!}
                    </x-rich-content>
                </div>
            @endif
        </div>
    </div>
@endsection

