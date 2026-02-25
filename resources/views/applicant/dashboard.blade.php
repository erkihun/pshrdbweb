@extends('layouts.public')

@php
    $statusStyles = [
        'submitted' => 'bg-blue-100 text-blue-700',
        'under_review' => 'bg-amber-100 text-amber-700',
        'shortlisted' => 'bg-emerald-100 text-emerald-700',
        'rejected' => 'bg-rose-100 text-rose-700',
        'hired' => 'bg-emerald-200 text-emerald-800',
        'withdrawn' => 'bg-slate-100 text-slate-700',
    ];

    $summaryCards = [
        [
            'label' => __('vacancies.public.summary_total'),
            'count' => $summary['total'],
            'icon' => 'briefcase',
            'tone' => 'blue',
        ],
        [
            'label' => __('vacancies.public.summary_submitted'),
            'count' => $summary['submitted'],
            'icon' => 'paper-airplane',
            'tone' => 'orange',
        ],
        [
            'label' => __('vacancies.public.summary_shortlisted'),
            'count' => $summary['shortlisted'],
            'icon' => 'check-circle',
            'tone' => 'emerald',
        ],
        [
            'label' => __('vacancies.public.summary_rejected'),
            'count' => $summary['rejected'],
            'icon' => 'x-circle',
            'tone' => 'rose',
        ],
        [
            'label' => __('vacancies.public.summary_hired'),
            'count' => $summary['hired'],
            'icon' => 'star',
            'tone' => 'blue',
        ],
    ];
@endphp

@section('content')
    <div class="mx-auto max-w-6xl space-y-8 py-12">
        @php
            $hasLockedApplications = $applications->getCollection()->contains(function ($application) {
                return $application->vacancy?->deadline && now()->gt($application->vacancy->deadline);
            });
        @endphp
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">{{ __('vacancies.public.dashboard_label') }}</p>
            <h1 class="text-3xl font-bold text-slate-900">{{ __('vacancies.public.dashboard_title', ['name' => $user->name]) }}</h1>
            <p class="text-sm text-slate-500">{{ __('vacancies.public.dashboard_subtitle') }}</p>
        </div>

        @if($hasLockedApplications)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700">
                Some applications are locked because their deadlines have passed.
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($summaryCards as $card)
                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="rounded-2xl bg-slate-50 p-2 text-slate-600">
                            <x-dynamic-component :component="'heroicon-o-' . $card['icon']" class="h-5 w-5" aria-hidden="true" />
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $card['label'] }}</span>
                    </div>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $card['count'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.public.applications_heading') }}</h2>
                <span class="text-sm text-slate-500">{{ $applications->total() }} {{ __('vacancies.public.count_label') }}</span>
            </div>

            @if($applications->count() > 0)
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="py-3 pr-4">{{ __('vacancies.public.vacancy') }}</th>
                                <th class="py-3 pr-4">{{ __('vacancies.public.reference_code') }}</th>
                                <th class="py-3 pr-4">{{ __('vacancies.public.status') }}</th>
                                <th class="py-3 pr-4">{{ __('vacancies.public.submitted_at') }}</th>
                                <th class="py-3 pr-4">{{ __('vacancies.public.deadline') }}</th>
                                <th class="py-3 text-right">{{ __('vacancies.public.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($applications as $application)
                                @php
                                    $deadline = $application->vacancy?->deadline;
                                    $deadlinePassed = $deadline && now()->gt($deadline);
                                @endphp
                                <tr class="align-top">
                                    <td class="py-4 pr-4 font-semibold text-slate-900">
                                        {{ $application->vacancy?->title ?? __('common.labels.not_available') }}
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">{{ $application->reference_code }}</td>
                                    <td class="py-4 pr-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusStyles[$application->status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $application->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">{{ $application->created_at?->format('M d, Y') }}</td>
                                    <td class="py-4 pr-4 text-slate-600">
                                        {{ $application->vacancy?->displayDeadlineLabel() ?? __('common.labels.not_available') }}
                                        @if($deadline)
                                            <div class="mt-1 text-xs text-slate-400">
                                                {{ $deadlinePassed ? 'Closed ' : 'Closes ' }}{{ $deadline->diffForHumans() }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a
                                                href="{{ route('applicant.applications.show', $application) }}"
                                                class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600"
                                            >
                                                {{ __('common.actions.view') }}
                                            </a>
                                            <a
                                                href="{{ route('applicant.applications.download', $application) }}"
                                                class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600"
                                            >
                                                {{ __('common.actions.download') }}
                                            </a>
                                            @if($application->status === \App\Models\VacancyApplication::STATUS_SUBMITTED && ! $deadlinePassed)
                                                <form method="POST" action="{{ route('applicant.applications.withdraw', $application) }}">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="rounded-full border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50"
                                                        onclick="return confirm('{{ __('vacancies.public.withdraw_confirm') }}')"
                                                    >
                                                        {{ __('vacancies.public.withdraw') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                        <x-heroicon-o-briefcase class="h-7 w-7 text-blue-600" aria-hidden="true" />
                    </div>
                    <p class="mt-4 text-sm font-semibold text-slate-900">{{ __('vacancies.public.applications_empty') }}</p>
                    <p class="mt-1 text-sm text-slate-500">Browse vacancies and submit your first application.</p>
                    <a
                        href="{{ route('vacancies.index') }}"
                        class="mt-4 inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-2 text-xs font-semibold text-white hover:bg-blue-500"
                    >
                        {{ __('vacancies.public.apply_now') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
