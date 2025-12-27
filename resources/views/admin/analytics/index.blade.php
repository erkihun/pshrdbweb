@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('ui.analytics') }}</h1>
            <p class="text-sm text-slate-500">Snapshot-driven bureau insights for public servants by organization, gender, and category.</p>
        </div>

        <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-6">
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-400">Period type</label>
                    <select name="period_type" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="monthly" @selected($filters['period_type'] === 'monthly')>Monthly</option>
                        <option value="quarterly" @selected($filters['period_type'] === 'quarterly')>Quarterly</option>
                        <option value="yearly" @selected($filters['period_type'] === 'yearly')>Yearly</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-400">Year</label>
                    <input
                        type="number"
                        name="year"
                        min="2000"
                        max="2100"
                        value="{{ $filters['year'] ?? now()->year }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-400">Month</label>
                    <select name="month" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" @selected($filters['month'] === $month)>{{ \DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-400">Quarter</label>
                    <select name="quarter" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All</option>
                        @foreach(range(1, 4) as $quarter)
                            <option value="{{ $quarter }}" @selected($filters['quarter'] === $quarter)>Q{{ $quarter }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-400">Dimension</label>
                    <select id="dimensionSelect" name="dimension" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        @if(count($dimensionOptions))
                            @foreach($dimensionOptions as $dimension)
                                <option value="{{ $dimension }}" @selected($filters['dimension'] === $dimension)>{{ Str::headline($dimension) }}</option>
                            @endforeach
                        @else
                            <option value="">No dimension</option>
                        @endif
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">Apply filters</button>
                </div>
            </div>
        </form>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Total employees</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['total'] ?? 0) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Male</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($summary['male'] ?? 0) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Female</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($summary['female'] ?? 0) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Other</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($summary['other'] ?? 0) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">Active organizations</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($activeOrganizations) }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Gender share</p>
                        <h2 class="text-lg font-semibold text-slate-900">Distribution</h2>
                    </div>
                    <span class="text-xs text-slate-500">Based on selected filters</span>
                </div>
                <div class="mt-6">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Dimension breakdown</p>
                        <h2 class="text-lg font-semibold text-slate-900">{{ $selectedDimension ? Str::headline($selectedDimension) : 'Dimensions' }}</h2>
                    </div>
                </div>
                <div class="mt-4 h-64">
                    <canvas id="dimensionChart"></canvas>
                </div>
                <div class="mt-4 space-y-2" data-dimension-segments>
                    @forelse($selectedDimensionSegments as $segment)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $segment['segment'] }}</p>
                                <p class="text-[10px] uppercase tracking-wide text-slate-400">
                                    Male {{ number_format($segment['male']) }} • Female {{ number_format($segment['female']) }} • Other {{ number_format($segment['other']) }}
                                </p>
                            </div>
                            <span class="text-xs font-semibold text-slate-500">Total {{ number_format($segment['total']) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No segment data for the selected dimension.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Monthly trend</p>
                        <h2 class="text-lg font-semibold text-slate-900">Year {{ $filters['year'] ?? 'N/A' }}</h2>
                    </div>
                    <span class="text-xs text-slate-500">Monthly totals</span>
                </div>
                <div class="mt-4">
                    @if($monthlyTrend['hasData'])
                        <canvas id="monthlyTrendChart"></canvas>
                    @else
                        <p class="text-sm text-slate-500">No monthly data yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Top organizations</p>
                    <h2 class="text-lg font-semibold text-slate-900">Headcount leaders</h2>
                </div>
                <span class="text-xs text-slate-500">Top {{ count($topOrganizations) }}</span>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($topOrganizations as $org)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $org['name'] }}</p>
                            <p class="text-xs uppercase tracking-wide text-slate-400">
                                Male {{ number_format($org['male']) }} • Female {{ number_format($org['female']) }} • Other {{ number_format($org['other']) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-semibold text-slate-900">{{ number_format($org['total']) }}</p>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Headcount</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No organizations recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const genderPayload = @json($genderChart);
        const dimensionPayload = @json($dimensionChartData);
        const monthlyTrend = @json($monthlyTrend);
        const numberFormatter = new Intl.NumberFormat();

        const genderCtx = document.getElementById('genderChart');
        if (genderCtx) {
            new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: genderPayload.labels,
                    datasets: [{
                        data: genderPayload.data,
                        backgroundColor: ['rgba(37,99,235,0.8)', 'rgba(16,185,129,0.8)', 'rgba(234,179,8,0.8)'],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: context => `${context.label}: ${numberFormatter.format(context.parsed)}`
                            }
                        }
                    }
                }
            });
        }

        const dimensionCtx = document.getElementById('dimensionChart');
        let dimensionChartInstance = null;
        if (dimensionCtx) {
            dimensionChartInstance = new Chart(dimensionCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Male',
                            data: [],
                            backgroundColor: 'rgba(37,99,235,0.8)',
                        },
                        {
                            label: 'Female',
                            data: [],
                            backgroundColor: 'rgba(16,185,129,0.8)',
                        },
                        {
                            label: 'Other',
                            data: [],
                            backgroundColor: 'rgba(234,179,8,0.8)',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                        },
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: context => `${context.dataset.label}: ${numberFormatter.format(context.parsed.y)}`
                            }
                        }
                    }
                },
            });
        }

        const monthlyCtx = document.getElementById('monthlyTrendChart');
        if (monthlyCtx && monthlyTrend.hasData) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyTrend.labels,
                    datasets: [{
                        label: 'Total employees',
                        data: monthlyTrend.data,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        }

        const dimensionSelect = document.getElementById('dimensionSelect');
        const segmentContainer = document.querySelector('[data-dimension-segments]');
        function updateDimensionChart(dimension) {
            if (!dimensionChartInstance || !dimension || !dimensionPayload[dimension]) {
                return;
            }

            const payload = dimensionPayload[dimension];
            dimensionChartInstance.data.labels = payload.labels;
            dimensionChartInstance.data.datasets[0].data = payload.male;
            dimensionChartInstance.data.datasets[1].data = payload.female;
            dimensionChartInstance.data.datasets[2].data = payload.other;
            dimensionChartInstance.update();

            if (segmentContainer) {
                segmentContainer.innerHTML = payload.segments.map(segment => `
                    <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                        <div>
                            <p class="font-semibold text-slate-900">${segment.segment}</p>
                            <p class="text-[10px] uppercase tracking-wide text-slate-400">
                                Male ${numberFormatter.format(segment.male)} • Female ${numberFormatter.format(segment.female)} • Other ${numberFormatter.format(segment.other)}
                            </p>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">Total ${numberFormatter.format(segment.total)}</span>
                    </div>
                `).join('') || '<p class="text-sm text-slate-500">No segment data for this dimension.</p>';
            }
        }

        if (dimensionSelect) {
            updateDimensionChart(dimensionSelect.value);
            dimensionSelect.addEventListener('change', function () {
                updateDimensionChart(this.value);
            });
        }
    </script>
@endsection
