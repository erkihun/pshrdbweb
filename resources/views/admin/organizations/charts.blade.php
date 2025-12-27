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
                    Code {{ $organization->code ?? 'N/A' }} &bull; {{ $organization->is_active ? 'Active' : 'Inactive' }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.organizations.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Back to list</a>
                <a href="{{ route('admin.organizations.charts', ['organization' => $organization]) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">Refresh</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-4 py-3">
                <a href="{{ route('admin.organizations.show', $organization) }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-900 {{ request()->routeIs('admin.organizations.show') && request('tab') !== 'stats' ? 'bg-slate-100 text-slate-900' : '' }}">Overview</a>
                <a href="{{ route('admin.organizations.show', ['organization' => $organization, 'tab' => 'stats']) }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-900 {{ request()->routeIs('admin.organizations.show') && request('tab') === 'stats' ? 'bg-slate-100 text-slate-900' : '' }}">Stats</a>
                <span class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Charts</span>
            </div>
            <div class="p-6">
                <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm sm:grid-cols-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Year</label>
                        <select name="year" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value=""></option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" @selected((string) $filterYear === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Month</label>
                        <select name="month" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value=""></option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" @selected((string) $filterMonth === (string) $month)>{{ \DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2 flex items-end gap-2">
                        <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">Apply filters</button>
                        <a href="{{ route('admin.organizations.charts', ['organization' => $organization]) }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Total employees</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Male</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['male']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Female</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['female']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Other</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['other']) }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Gender distribution</p>
                        <h2 class="text-lg font-semibold text-slate-900">By gender</h2>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse gap-6 lg:flex-row-reverse lg:items-center">
                    <div class="w-full lg:w-1/2">
                        <canvas id="genderChart" height="300"></canvas>
                    </div>
                    <div class="space-y-2 text-sm text-slate-600 lg:w-1/2 lg:pr-4">
                        <p>Rows reflect the selected filters (year/month).</p>
                        <p>If no data is available the chart will display zeros.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Dimension breakdown</p>
                        <h2 class="text-lg font-semibold text-slate-900">By segment</h2>
                    </div>
                    @if(count($dimensionData))
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dimension</label>
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
                            No segments recorded yet for the selected filters.
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Monthly trend</p>
                        <h2 class="text-lg font-semibold text-slate-900">Yearly progress</h2>
                    </div>
                </div>
                <div class="mt-6">
                    @if($filterYear && count($monthlyTrend))
                        <canvas id="trendChart" height="240"></canvas>
                    @else
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                            Select a year to visualize monthly trends.
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
                            label: 'Male',
                            data: data.male,
                            backgroundColor: '#1d4ed8',
                        },
                        {
                            label: 'Female',
                            data: data.female,
                            backgroundColor: '#be185d',
                        },
                        {
                            label: 'Other',
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
                        labels: trendKeys.map((month) => new Date(0, Number(month) - 1).toLocaleString('en', { month: 'short' })),
                        datasets: [{
                            label: '{{ $filterYear }} totals',
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
