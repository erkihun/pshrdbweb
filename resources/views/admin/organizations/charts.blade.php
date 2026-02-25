@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.resources_group') }}</p>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $organization->name }}</h1>
                <p class="text-sm text-slate-500">
                    {{ __('common.admin_organizations.registered.with_code', ['code' => $organization->code ?? __('common.labels.not_available')]) }}
                    &bull;
                    {{ $organization->is_active ? __('common.admin_organizations.status.active') : __('common.admin_organizations.status.inactive') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.organizations.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">{{ __('common.admin_organizations.charts.actions.back_to_list') }}</a>
                <a href="{{ route('admin.organizations.charts', ['organization' => $organization]) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">{{ __('common.admin_organizations.charts.actions.refresh') }}</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-4 py-3">
                <a href="{{ route('admin.organizations.show', $organization) }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-900 {{ request()->routeIs('admin.organizations.show') && request('tab') !== 'stats' ? 'bg-slate-100 text-slate-900' : '' }}">{{ __('common.admin_organizations.tabs.overview') }}</a>
                <a href="{{ route('admin.organizations.show', ['organization' => $organization, 'tab' => 'stats']) }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-900 {{ request()->routeIs('admin.organizations.show') && request('tab') === 'stats' ? 'bg-slate-100 text-slate-900' : '' }}">{{ __('common.admin_organizations.tabs.stats') }}</a>
                <span class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white">{{ __('common.admin_organizations.charts.tabs.charts') }}</span>
            </div>
            <div class="p-6">
                <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm sm:grid-cols-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.filters.year') }}</label>
                        <select name="year" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value=""></option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" @selected((string) $filterYear === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.filters.month') }}</label>
                        <select name="month" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value=""></option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" @selected((string) $filterMonth === (string) $month)>{{ \Carbon\Carbon::create()->month($month)->locale(app()->getLocale())->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2 flex items-end gap-2">
                        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">{{ __('common.admin_organizations.filters.apply') }}</button>
                        <a href="{{ route('admin.organizations.charts', ['organization' => $organization]) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">{{ __('common.admin_organizations.filters.reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.charts.cards.total_employees') }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.metrics.male') }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['male']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.metrics.female') }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['female']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.metrics.other') }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['other']) }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.charts.sections.gender_distribution.kicker') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('common.admin_organizations.charts.sections.gender_distribution.title') }}</h2>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse gap-6 lg:flex-row-reverse lg:items-center">
                    <div class="w-full lg:w-1/2">
                        <canvas id="genderChart" height="300"></canvas>
                    </div>
                    <div class="space-y-2 text-sm text-slate-600 lg:w-1/2 lg:pr-4">
                        <p>{{ __('common.admin_organizations.charts.sections.gender_distribution.note_1') }}</p>
                        <p>{{ __('common.admin_organizations.charts.sections.gender_distribution.note_2') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.charts.sections.dimension_breakdown.kicker') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('common.admin_organizations.charts.sections.dimension_breakdown.title') }}</h2>
                    </div>
                    @if(count($dimensionData))
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.charts.sections.dimension_breakdown.dimension_label') }}</label>
                            <select id="dimensionSelect" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($dimensionData as $dimension => $data)
                                    <option value="{{ $dimension }}" @selected($selectedDimension === $dimension)>{{ Str::headline($dimension) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="mt-6">
                    @if(count($dimensionData))
                        <canvas id="dimensionChart" height="320"></canvas>
                    @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                            {{ __('common.admin_organizations.charts.sections.dimension_breakdown.empty') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.charts.sections.monthly_trend.kicker') }}</p>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('common.admin_organizations.charts.sections.monthly_trend.title') }}</h2>
                    </div>
                </div>
                <div class="mt-6">
                    @if($filterYear && count($monthlyTrend))
                        <canvas id="trendChart" height="240"></canvas>
                    @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                            {{ __('common.admin_organizations.charts.sections.monthly_trend.empty') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const genderData = @json($genderDataset);
            const hasStats = {{ $hasStats ? 'true' : 'false' }};
            const dimensionData = @json($dimensionData);
            const monthlyTrend = @json($monthlyTrend);
            const defaultDimension = "{{ $selectedDimension }}";

            const genderCtx = document.getElementById('genderChart');
            if (genderCtx) {
                new Chart(genderCtx, {
                    type: 'doughnut',
                    data: {
                        labels: genderData.labels,
                        datasets: [{
                            data: genderData.data,
                            backgroundColor: ['#2563eb', '#c026d3', '#f97316'],
                            borderWidth: 2,
                            borderColor: '#fff',
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 20 },
                            },
                        },
                    },
                });
            }

            if (Object.keys(dimensionData).length) {
                const dimensionCtx = document.getElementById('dimensionChart');
                const select = document.getElementById('dimensionSelect');
                let dimensionChart;

                const buildOptions = (data) => ({
                    labels: data.labels,
                    datasets: [
                        {
                            label: @json(__('common.admin_organizations.metrics.male')),
                            data: data.male,
                            backgroundColor: '#1d4ed8',
                        },
                        {
                            label: @json(__('common.admin_organizations.metrics.female')),
                            data: data.female,
                            backgroundColor: '#be185d',
                        },
                        {
                            label: @json(__('common.admin_organizations.metrics.other')),
                            data: data.other,
                            backgroundColor: '#f59e0b',
                        },
                    ],
                });

                const createDimensionChart = (data) => {
                    if (dimensionChart) {
                        dimensionChart.destroy();
                    }
                    dimensionChart = new Chart(dimensionCtx, {
                        type: 'bar',
                        data: buildOptions(data),
                        options: {
                            responsive: true,
                            scales: {
                                x: { stacked: true },
                                y: { stacked: true, beginAtZero: true },
                            },
                        },
                    });
                };

                const initialDimension = select.value || defaultDimension || Object.keys(dimensionData)[0];
                createDimensionChart(dimensionData[initialDimension]);

                select.addEventListener('change', (event) => {
                    const value = event.target.value;
                    if (dimensionData[value]) {
                        createDimensionChart(dimensionData[value]);
                    }
                });
            }

            const trendKeys = Object.keys(monthlyTrend);
            if (trendKeys.length && document.getElementById('trendChart')) {
                const trendCtx = document.getElementById('trendChart');
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendKeys.map((month) => new Date(0, Number(month) - 1).toLocaleString(@json(app()->getLocale()), { month: 'short' })),
                        datasets: [{
                            label: @json(__('common.admin_organizations.charts.sections.monthly_trend.dataset_label', ['year' => $filterYear])),
                            data: trendKeys.map((month) => monthlyTrend[month]),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.2)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#2563eb',
                        }],
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true },
                        },
                    },
                });
            }
        });
    </script>
@endsection
