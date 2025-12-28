@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Website Analytics &amp; Analysis</h1>
            <p class="text-sm text-slate-500">
                Track visits, user engagement, and trends so the portal team can measure outreach, prioritize performance work, and stay ahead of bottlenecks.
            </p>
        </div>

        <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-6">
                <div class="md:col-span-2">
                    <label class="text-xs uppercase tracking-wide text-slate-400">Start date</label>
                    <input
                        type="date"
                        name="start"
                        value="{{ $filters['start'] }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs uppercase tracking-wide text-slate-400">End date</label>
                    <input
                        type="date"
                        name="end"
                        value="{{ $filters['end'] }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-100"
                    >
                        Apply filters
                    </button>
                </div>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ([
                ['label' => 'Pageviews', 'value' => $metrics['pageviews']],
                ['label' => 'Website visitors', 'value' => $metrics['visits']],
                ['label' => 'Unique visitors', 'value' => $metrics['unique_visitors']],
                ['label' => 'Authenticated users', 'value' => $metrics['authenticated_users']],
                ['label' => 'Total users', 'value' => $metrics['total_users']],
                ['label' => 'Active users (30d)', 'value' => $metrics['active_users']],
            ] as $card)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ $card['label'] }}</div>
                    <div class="mt-3 text-2xl font-semibold text-slate-900">{{ number_format($card['value']) }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Trend</p>
                        <h2 class="text-lg font-semibold text-slate-900">Daily pageviews and visits</h2>
                    </div>
                    <span class="text-sm text-slate-500"> {{ $filters['start'] }} → {{ $filters['end'] }} </span>
                </div>
                <div class="mt-6">
                    <canvas id="analysisTrendChart" class="w-full h-72"></canvas>
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Devices</p>
                            <h2 class="text-lg font-semibold text-slate-900">Device breakdown</h2>
                        </div>
                    </div>
                    <div class="mt-4 space-y-3">
                        @foreach ($deviceBreakdown as $device)
                            <div class="flex items-center justify-between text-sm text-slate-700">
                                <span class="font-medium text-slate-900">{{ $device['type'] }}</span>
                                <span>{{ number_format($device['count']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Browsers</p>
                            <h2 class="text-lg font-semibold text-slate-900">Top browsers</h2>
                        </div>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($browserBreakdown as $browser)
                            <div class="flex items-center justify-between text-sm text-slate-700">
                                <span class="font-medium text-slate-900">{{ $browser->browser }}</span>
                                <span>{{ number_format($browser->count) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No browser data yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Top Pages</p>
                        <h2 class="text-lg font-semibold text-slate-900">Most visited paths</h2>
                    </div>
                </div>
                <div class="mt-5 border-t border-slate-100">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-slate-500">
                                <th class="py-3 font-semibold">Path</th>
                                <th class="py-3 font-semibold text-right">Pageviews</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topPages as $page)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 text-slate-700">
                                        <span class="text-sm font-medium text-slate-900">{{ $page->path }}</span>
                                    </td>
                                    <td class="py-3 text-right font-semibold text-slate-900">{{ number_format($page->hits) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-sm text-slate-500">No pageviews recorded for this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Referrers</p>
                        <h2 class="text-lg font-semibold text-slate-900">Where visitors came from</h2>
                    </div>
                </div>
                <div class="mt-5 border-t border-slate-100">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-slate-500">
                                <th class="py-3 font-semibold">Referrer</th>
                                <th class="py-3 font-semibold text-right">Sessions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($referrers as $referrer)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 text-slate-700">{{ $referrer->referrer }}</td>
                                    <td class="py-3 text-right font-semibold text-slate-900">{{ number_format($referrer->hits) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 text-sm text-slate-500">No referrals captured for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const trendCtx = document.getElementById('analysisTrendChart');
        if (trendCtx) {
            new Chart(trendCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($trend['labels']),
                    datasets: [
                        {
                            label: 'Pageviews',
                            data: @json($trend['pageviews']),
                            borderColor: 'rgba(37, 99, 235, 0.8)',
                            backgroundColor: 'rgba(37, 99, 235, 0.2)',
                            tension: 0.3,
                        },
                        {
                            label: 'Visits',
                            data: @json($trend['visits']),
                            borderColor: 'rgba(16, 185, 129, 0.8)',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            tension: 0.3,
                        },
                    ],
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
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: '#64748B',
                            },
                        },
                        y: {
                            grid: {
                                color: 'rgba(15, 23, 42, 0.08)',
                            },
                            ticks: {
                                color: '#64748B',
                                beginAtZero: true,
                            },
                        },
                    },
                },
            });
        }
    </script>
@endpush
