@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4 rounded-xl bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-900">{{ __('vacancies.application.heading') }}</h1>
                    <p class="text-sm text-slate-500">{{ __('vacancies.application.subheading') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.vacancies.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                    >
                        <span>{{ __('common.actions.back') }}</span>
                    </a>
                    @if($selectedVacancy)
                        <a
                            href="{{ route('admin.vacancies.show', $selectedVacancy) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500"
                        >
                            {{ __('vacancies.application.view_vacancy') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="grid gap-4 md:grid-cols-6" method="GET" action="{{ route('admin.vacancies.applications.index') }}">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="search">{{ __('common.actions.search') }}</label>
                    <input
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        type="search"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        placeholder="{{ __('vacancies.application.search_placeholder') }}"
                    >
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="vacancy_id">{{ __('common.labels.vacancy') }}</label>
                    <select
                        id="vacancy_id"
                        name="vacancy_id"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        <option value="">{{ __('common.labels.all') }}</option>
                        @foreach($vacancies as $vacancyOption)
                            <option value="{{ $vacancyOption->id }}" @selected(request('vacancy_id') === $vacancyOption->id || $selectedVacancy === $vacancyOption->id)>{{ $vacancyOption->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="status">{{ __('common.labels.status') }}</label>
                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                        <option value="">{{ __('common.labels.all') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ __('common.status.' . $status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="from">{{ __('vacancies.application.filter_from') }}</label>
                    <input
                        id="from"
                        name="from"
                        type="date"
                        value="{{ request('from') }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="to">{{ __('vacancies.application.filter_to') }}</label>
                    <input
                        id="to"
                        name="to"
                        type="date"
                        value="{{ request('to') }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                </div>
                <div class="md:col-span-6">
                    <div class="flex flex-wrap gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                        >
                            <span>{{ __('common.actions.filter') }}</span>
                        </button>
                        <a href="{{ route('admin.vacancies.applications.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            {{ __('common.actions.clear') }}
                        </a>
                        <a
                            href="{{ route('admin.vacancies.applications.export', request()->only(['vacancy_id', 'status', 'from', 'to'])) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-5 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100"
                        >
                            {{ __('vacancies.application.export_excel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.full_name') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.vacancy') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.email') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.phone') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.submitted_at') }}</th>
                            <th class="px-6 py-4 text-left">{{ __('common.labels.status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('common.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($applications as $application)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $application->full_name }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $application->vacancy->title ?? __('common.labels.unknown') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $application->email }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $application->phone ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $application->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $application->status === 'rejected' ? 'bg-rose-100 text-rose-600' : ($application->status === 'hired' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $application->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.vacancies.applications.show', $application) }}"
                                            class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                        >
                                            {{ __('common.actions.view') }}
                                        </a>
                                        <a
                                            href="{{ route('admin.vacancies.applications.download', $application) }}"
                                            class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50"
                                        >
                                            {{ __('common.actions.download') }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.vacancies.applications.destroy', $application) }}" onsubmit="return confirm('{{ __('vacancies.application.confirm_delete') }}')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                                {{ __('common.actions.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                    {{ __('vacancies.application.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            {{ $applications->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection
