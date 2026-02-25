@extends('admin.layouts.app')

@php
    $statusOptions = \App\Models\VacancyApplication::statuses();
@endphp

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.heading') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('vacancies.admin.analytics.title') }}</h1>
            <p class="text-sm text-slate-500">
                {{ __('vacancies.admin.analytics.subtitle') }}
            </p>
        </div>

        <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-12">
                <div class="md:col-span-3">
                    <label class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.from') }}</label>
                    <input
                        type="date"
                        name="from"
                        value="{{ $filters['from'] }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.to') }}</label>
                    <input
                        type="date"
                        name="to"
                        value="{{ $filters['to'] }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.vacancy') }}</label>
                    <select
                        name="vacancy"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">{{ __('vacancies.admin.analytics.all_vacancies') }}</option>
                        @foreach($vacancies as $vacancy)
                            <option value="{{ $vacancy->id }}" @selected($filters['vacancy'] === $vacancy->id)>
                                {{ $vacancy->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.status') }}</label>
                    <select
                        name="status"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">{{ __('vacancies.admin.analytics.all_statuses') }}</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" @selected($filters['status'] === $status)>
                                {{ __('common.status.' . $status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1 flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-100"
                    >
                        {{ __('common.actions.filter') }}
                    </button>
                </div>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => __('vacancies.admin.analytics.kpi_total_vacancies'), 'value' => $kpis['total_vacancies']],
                ['label' => __('vacancies.admin.analytics.kpi_total_applications'), 'value' => $kpis['total_applications']],
                ['label' => __('vacancies.admin.analytics.kpi_applications_today'), 'value' => $kpis['applications_today']],
                ['label' => __('vacancies.admin.analytics.kpi_applications_month'), 'value' => $kpis['applications_month']],
                ['label' => __('common.status.shortlisted'), 'value' => $kpis['shortlisted']],
                ['label' => __('common.status.rejected'), 'value' => $kpis['rejected']],
                ['label' => __('common.status.hired'), 'value' => $kpis['hired']],
            ] as $card)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ $card['label'] }}</div>
                    <div class="mt-3 text-2xl font-semibold text-slate-900">{{ number_format($card['value']) }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.gender_breakdown') }}</div>
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>{{ __('vacancies.public.male') }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($insights['gender']['male']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('vacancies.public.female') }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($insights['gender']['female']) }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.average_gpa') }}</div>
                <div class="mt-4 text-3xl font-semibold text-slate-900">
                    {{ $insights['avg_gpa'] !== null ? number_format($insights['avg_gpa'], 2) : __('vacancies.admin.analytics.not_available') }}
                </div>
                <p class="mt-2 text-sm text-slate-500">{{ __('vacancies.admin.analytics.average_gpa_hint') }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.age_distribution') }}</div>
                <div class="mt-4 space-y-2 text-sm text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>{{ __('vacancies.admin.analytics.age_18_25') }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($insights['age_buckets']['18-25']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('vacancies.admin.analytics.age_26_35') }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($insights['age_buckets']['26-35']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('vacancies.admin.analytics.age_36_45') }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($insights['age_buckets']['36-45']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ __('vacancies.admin.analytics.age_46_plus') }}</span>
                        <span class="font-semibold text-slate-900">{{ number_format($insights['age_buckets']['46+']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.applications') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.admin.analytics.applications_over_time') }}</h2>
                    </div>
                    <span class="text-sm text-slate-500">
                        {{ $filters['from'] ?? now()->subDays(29)->format('Y-m-d') }} {{ __('vacancies.admin.analytics.date_range_separator') }} {{ $filters['to'] ?? now()->format('Y-m-d') }}
                    </span>
                </div>
                <div class="mt-6">
                    <canvas id="vacancyTrendChart" class="h-72 w-full"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.status') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.admin.analytics.status_distribution') }}</h2>
                    </div>
                </div>
                <div class="mt-6">
                    <canvas id="vacancyStatusChart" class="h-72 w-full"></canvas>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('vacancies.admin.analytics.demand') }}</p>
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('vacancies.admin.analytics.applications_per_vacancy') }}</h2>
                </div>
            </div>
            <div class="mt-6">
                <canvas id="vacancyPerChart" class="h-80 w-full"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = {
            trend: {
                labels: @json($charts['trend']['labels']),
                values: @json($charts['trend']['values']),
            },
            status: {
                labels: @json($charts['status']['labels']),
                values: @json($charts['status']['values']),
            },
            perVacancy: {
                labels: @json($charts['perVacancy']['labels']),
                values: @json($charts['perVacancy']['values']),
            },
        };

        const charts = {};

        const buildCharts = () => {
            if (charts.trend) charts.trend.destroy();
            if (charts.status) charts.status.destroy();
            if (charts.perVacancy) charts.perVacancy.destroy();

            const trendCtx = document.getElementById('vacancyTrendChart');
            if (trendCtx) {
                charts.trend = new Chart(trendCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartData.trend.labels,
                        datasets: [{
                            label: @json(__('vacancies.admin.analytics.applications')),
                            data: chartData.trend.values,
                            borderColor: 'rgba(37, 99, 235, 0.9)',
                            backgroundColor: 'rgba(37, 99, 235, 0.15)',
                            tension: 0.35,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748B' },
                            },
                            y: {
                                grid: { color: 'rgba(15, 23, 42, 0.08)' },
                                ticks: { color: '#64748B', beginAtZero: true },
                            },
                        },
                    },
                });
            }

            const statusCtx = document.getElementById('vacancyStatusChart');
            if (statusCtx) {
                charts.status = new Chart(statusCtx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: chartData.status.labels,
                        datasets: [{
                            data: chartData.status.values,
                            backgroundColor: [
                                'rgba(37, 99, 235, 0.75)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(248, 113, 113, 0.85)',
                                'rgba(20, 184, 166, 0.8)',
                                'rgba(148, 163, 184, 0.7)',
                            ],
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                        },
                    },
                });
            }

            const perVacancyCtx = document.getElementById('vacancyPerChart');
            if (perVacancyCtx) {
                charts.perVacancy = new Chart(perVacancyCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.perVacancy.labels,
                        datasets: [{
                            label: @json(__('vacancies.admin.analytics.applications')),
                            data: chartData.perVacancy.values,
                            backgroundColor: 'rgba(59, 130, 246, 0.75)',
                            borderRadius: 10,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748B' },
                            },
                            y: {
                                grid: { color: 'rgba(15, 23, 42, 0.08)' },
                                ticks: { color: '#64748B', beginAtZero: true },
                            },
                        },
                    },
                });
            }
        };

        document.addEventListener('DOMContentLoaded', buildCharts);
    </script>
@endpush




