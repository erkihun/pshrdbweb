@extends('layouts.public')

@php
    $breadcrumbItems = [
        ['label' => __('vacancies.public.title'), 'url' => route('vacancies.index')],
        ['label' => __('vacancies.public.success_title'), 'url' => route('vacancies.apply.success', ['code' => $application->reference_code])],
    ];
@endphp

@section('content')
    <div class="mx-auto max-w-3xl space-y-8 py-12">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50">
                <x-heroicon-o-check-circle class="h-9 w-9 text-emerald-600" aria-hidden="true" />
            </div>
            <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ __('vacancies.public.success_status') }}</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ __('vacancies.public.success_title') }}</h1>
            <p class="mt-2 text-base text-slate-500">{{ __('vacancies.public.success_message') }}</p>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-6 py-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('vacancies.public.reference_code') }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900" id="reference-code">{{ $application->reference_code }}</p>
                <button
                    type="button"
                    class="mt-4 inline-flex items-center justify-center rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100"
                    data-copy-reference
                >
                    Copy code
                </button>
            </div>

            <p class="mt-4 text-sm text-slate-500">{{ __('vacancies.public.success_note') }}</p>

            <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a
                    href="{{ route('vacancies.track') }}"
                    class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    {{ __('vacancies.public.track_button') }}
                </a>
                <a
                    href="{{ auth('applicant')->check() ? route('applicant.dashboard') : route('applicant.login') }}"
                    class="inline-flex items-center justify-center rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600"
                >
                    {{ __('vacancies.public.dashboard_label') }}
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Next steps</h2>
            <ol class="mt-4 space-y-3 text-sm text-slate-600">
                <li class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">1</span>
                    <span>Save your reference code for future tracking.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">2</span>
                    <span>Check your application status from the tracking page.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-xs font-semibold text-blue-600">3</span>
                    <span>Visit the applicant dashboard for downloads and updates.</span>
                </li>
            </ol>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const copyButton = document.querySelector('[data-copy-reference]');
            const code = document.getElementById('reference-code');
            if (!copyButton || !code) {
                return;
            }

            copyButton.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(code.textContent.trim());
                    copyButton.textContent = 'Copied';
                    setTimeout(() => {
                        copyButton.textContent = 'Copy code';
                    }, 2000);
                } catch (error) {
                    copyButton.textContent = 'Copy failed';
                }
            });
        });
    </script>
@endsection
