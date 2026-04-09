@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Vacancy Announcements</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $vacancy->title }}</h1>
                <p class="text-sm text-slate-500">Date: {{ optional($vacancy->deadline)->format('M d, Y') ?? __('common.labels.not_available') }}</p>
                <p class="mt-1 text-sm text-slate-500">
                    Status:
                    <span class="font-semibold {{ $vacancy->is_published ? 'text-emerald-700' : 'text-slate-700' }}">
                        {{ $vacancy->is_published ? 'Published' : 'Unpublished' }}
                    </span>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.vacancies.edit', $vacancy) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    {{ __('common.actions.edit') }}
                </a>
                <form method="POST" action="{{ route($vacancy->is_published ? 'admin.vacancies.unpublish' : 'admin.vacancies.publish', $vacancy) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-semibold {{ $vacancy->is_published ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                        {{ $vacancy->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.vacancies.destroy', $vacancy) }}" onsubmit="return confirm('{{ __('vacancies.admin.delete_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                        {{ __('common.actions.delete') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Main Content</h2>
            <div class="prose max-w-none text-slate-700">
                {!! $vacancy->description !!}
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.vacancies.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-800">Back to list</a>
        </div>
    </div>
@endsection
