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

    $timeline = [
        \App\Models\VacancyApplication::STATUS_SUBMITTED,
        \App\Models\VacancyApplication::STATUS_UNDER_REVIEW,
        \App\Models\VacancyApplication::STATUS_SHORTLISTED,
        \App\Models\VacancyApplication::STATUS_REJECTED,
        \App\Models\VacancyApplication::STATUS_HIRED,
        \App\Models\VacancyApplication::STATUS_WITHDRAWN,
    ];
    $deadline = $application->vacancy?->deadline;
    $deadlinePassed = $deadline && now()->gt($deadline);
@endphp

@section('content')
    <div class="mx-auto max-w-5xl space-y-8 py-12">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-500">{{ __('vacancies.public.application_details') }}</p>
                <h1 class="text-3xl font-bold text-slate-900">{{ $application->vacancy?->title ?? __('common.labels.not_available') }}</h1>
                <p class="text-sm text-slate-500">{{ __('vacancies.public.reference_code') }}: {{ $application->reference_code }}</p>
            </div>
            <span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-wide {{ $statusStyles[$application->status] ?? 'bg-slate-100 text-slate-700' }}">
                {{ $application->status_label }}
            </span>
        </div>

        @if($deadlinePassed)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700">
                Application updates are locked because the vacancy deadline has passed.
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.public.submitted_at') }}</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $application->created_at?->format('M d, Y') }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.public.deadline') }}</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">
                    {{ $application->vacancy?->displayDeadlineLabel() ?? __('common.labels.not_available') }}
                </p>
                @if($deadline)
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $deadlinePassed ? 'Closed ' : 'Closes ' }}{{ $deadline->diffForHumans() }}
                    </p>
                @endif
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.public.download_cv') }}</p>
                <a
                    href="{{ route('applicant.applications.download', $application) }}"
                    class="mt-2 inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600"
                >
                    {{ __('common.actions.download') }}
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.public.personal_info') }}</h2>
                <dl class="mt-4 space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.full_name') }}</dt>
                        <dd>{{ $application->full_name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.date_of_birth') }}</dt>
                        <dd>{{ $application->date_of_birth?->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.gender') }}</dt>
                        <dd>{{ __('vacancies.public.' . $application->gender) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.disability_status') }}</dt>
                        <dd>{{ $application->disability_status ? __('vacancies.public.yes') : __('vacancies.public.no') }}</dd>
                    </div>
                    @if($application->disability_status && $application->disability_type)
                        <div class="flex justify-between gap-4">
                            <dt class="font-semibold text-slate-700">{{ __('vacancies.public.disability_type') }}</dt>
                            <dd>{{ $application->disability_type }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.public.education_details') }}</h2>
                <dl class="mt-4 space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.education_level') }}</dt>
                        <dd>{{ $application->education_level }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.field_of_study') }}</dt>
                        <dd>{{ $application->field_of_study }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.university_name') }}</dt>
                        <dd>{{ $application->university_name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.graduation_year') }}</dt>
                        <dd>{{ $application->graduation_year }}</dd>
                    </div>
                    @if($application->gpa)
                        <div class="flex justify-between gap-4">
                            <dt class="font-semibold text-slate-700">{{ __('vacancies.public.gpa') }}</dt>
                            <dd>{{ $application->gpa }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.public.contact_info') }}</h2>
                <dl class="mt-4 space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.address') }}</dt>
                        <dd>{{ $application->address }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.phone_number') }}</dt>
                        <dd>{{ $application->phone }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.national_id_number') }}</dt>
                        <dd>{{ $application->national_id_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="font-semibold text-slate-700">{{ __('vacancies.public.email') }}</dt>
                        <dd>{{ $application->email }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.public.status_timeline') }}</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-600">
                    @foreach($timeline as $status)
                        <li class="flex items-center justify-between">
                            <span>{{ __('common.status.' . $status) }}</span>
                            @if($status === $application->status)
                                <span class="text-xs font-semibold uppercase tracking-wide text-blue-600">{{ __('vacancies.public.current_status') }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        @if($application->admin_note)
            <div class="rounded-3xl border border-amber-100 bg-amber-50 p-6 text-sm text-amber-700">
                <p class="font-semibold">{{ __('vacancies.public.admin_note') }}</p>
                <p class="mt-2">{{ $application->admin_note }}</p>
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('applicant.dashboard') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                {{ __('vacancies.public.back_dashboard') }}
            </a>
            @if($application->status === \App\Models\VacancyApplication::STATUS_SUBMITTED && ! $deadlinePassed)
                <form method="POST" action="{{ route('applicant.applications.withdraw', $application) }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50"
                        onclick="return confirm('{{ __('vacancies.public.withdraw_confirm') }}')"
                    >
                        {{ __('vacancies.public.withdraw') }}
                    </button>
                </form>
            @elseif($deadlinePassed)
                <span class="text-xs font-semibold text-slate-500">Withdrawal closed after deadline.</span>
            @endif
        </div>
    </div>
@endsection
