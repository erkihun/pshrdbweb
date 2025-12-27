@extends('layouts.public')

@section('title', __('common.nav.public_servant_dashboard'))

@section('content')
    <div class="min-h-screen bg-white py-16 font-noto-ethiopic">
        <div class="mx-auto max-w-6xl px-6 lg:px-8">
            <div class="mb-10 space-y-3 text-center">
                <p class="text-xs uppercase tracking-wider text-slate-400">{{ __('common.nav.public_servant_dashboard') }}</p>
                <h1 class="text-4xl font-semibold text-slate-900">Public Servant Dashboard</h1>
                <p class="text-sm text-slate-500 max-w-3xl mx-auto">
                    Explore the total counts for every registered organization. Filters and drill-down visualizations are available in the admin area.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-12">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Total servants</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['total']) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Male</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['male']) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Female</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['female']) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Other</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totals['other']) }}</p>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-white shadow-lg">
                <div class="divide-y divide-gray-100">
                    @foreach($summaries as $summary)
                        <div class="flex flex-col gap-3 px-6 py-5 hover:bg-slate-50 transition last:border-b-0 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-lg font-semibold text-slate-900">{{ $summary['name'] }}</p>
                                <p class="text-xs text-slate-500">Code {{ $summary['code'] ?? 'N/A' }}</p>
                            </div>
                            <div class="flex flex-wrap gap-3 text-sm font-semibold uppercase tracking-wide text-slate-500">
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600">Male {{ number_format($summary['male']) }}</span>
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600">Female {{ number_format($summary['female']) }}</span>
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600">Other {{ number_format($summary['other']) }}</span>
                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600">Total {{ number_format($summary['total']) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
