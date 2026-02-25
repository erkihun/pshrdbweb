@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.section_label') }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $vacancy->title }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.reference_code') }}: {{ $vacancy->code ?? __('common.labels.not_available') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.vacancies.edit', $vacancy) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    {{ __('common.actions.edit') }}
                </a>
                <form method="POST" action="{{ route('admin.vacancies.destroy', $vacancy) }}" onsubmit="return confirm('{{ __('vacancies.admin.delete_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">{{ __('common.actions.delete') }}</button>
                </form>
                <a
                    href="{{ route('admin.vacancies.applications.forVacancy', $vacancy) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500"
                >
                    {{ __('vacancies.application.view_list') }}
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase text-slate-400">{{ __('common.labels.status') }}</p>
                <p class="text-lg font-semibold text-slate-900">{{ __('common.status.' . $vacancy->status) }}</p>
                <p class="text-xs text-slate-500">{{ __('vacancies.public.deadline_label') }} {{ $vacancy->deadline_label }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase text-slate-400">{{ __('vacancies.admin.publish_window') }}</p>
                <p class="text-lg font-semibold text-slate-900">{{ optional($vacancy->published_at)->format('M d, Y') ?? __('vacancies.admin.publish_manual') }}</p>
                <p class="text-xs text-slate-500">{{ $vacancy->is_published ? __('common.status.published') : __('vacancies.admin.unpublished') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase text-slate-400">{{ __('vacancies.public.location') }}</p>
                <p class="text-lg font-semibold text-slate-900">{{ $vacancy->location ?? __('common.labels.not_available') }}</p>
                <p class="text-xs text-slate-500">{{ $vacancy->deadline_badge }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">{{ __('common.labels.description') }}</h2>
            <p class="text-sm leading-relaxed text-slate-700 whitespace-pre-line">{{ $vacancy->description }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('vacancies.admin.notes') }}</h3>
            <p class="text-sm leading-relaxed text-slate-700 whitespace-pre-line">{{ $vacancy->notes ?? __('common.labels.not_available') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <form method="POST" action="{{ route($vacancy->is_published ? 'admin.vacancies.unpublish' : 'admin.vacancies.publish', $vacancy) }}">
                @csrf
                <button type="submit" class="rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:from-blue-500">
                    {{ $vacancy->is_published ? __('vacancies.admin.unpublish_action') : __('vacancies.admin.publish_action') }}
                </button>
            </form>
            <a href="{{ route('admin.vacancies.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-800">{{ __('vacancies.admin.back_to_list') }}</a>
        </div>
    </div>
@endsection
