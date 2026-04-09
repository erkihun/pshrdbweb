@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">Vacancy Announcements</p>
                <h1 class="text-2xl font-semibold text-slate-900">Create Vacancy Announcement</h1>
                <p class="text-sm text-slate-500">Add a date, title, and main content for the announcement.</p>
            </div>
            <a href="{{ route('admin.vacancies.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Back to list
            </a>
        </div>

        <form method="POST" action="{{ route('admin.vacancies.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            @csrf
            @include('admin.vacancies._form')

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.vacancies.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">{{ __('common.actions.cancel') }}</a>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">Create Announcement</button>
            </div>
        </form>
    </div>
@endsection
