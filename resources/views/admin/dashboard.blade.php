@extends('admin.layouts.app')

@section('content')
@php
    $monthNames = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
    $typeLabels = [
        'department' => 'Department',
        'charter_service' => 'Charter Service',
        'organization' => 'Organization',
    ];
@endphp

<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl space-y-8">
        <div>
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Dashboard Overview</h1>
                    <p class="text-sm text-slate-500">Welcome back, {{ Auth::user()->name }}. Here’s what’s happening today.</p>
                </div>
                <form action="{{ route('admin.dashboard') }}" method="get" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="year-filter">Year</label>
                        <select id="year-filter" name="year" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All years</option>
                            @foreach($yearOptions as $year)
                                <option value="{{ $year }}" @selected($filters['year'] === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="month-filter">Month</label>
                        <select id="month-filter" name="month" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All months</option>
                            @foreach($monthNames as $value => $label)
                                <option value="{{ $value }}" @selected($filters['month'] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Apply</button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Organizations</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['organizationTotal']) }}</p>
                        <p class="text-sm text-slate-500">Active: {{ number_format($metrics['organizationActive']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Departments</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['departmentTotal']) }}</p>
                        <p class="text-sm text-slate-500">Active: {{ number_format($metrics['departmentActive']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">News Posts</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['newsPostsTotal']) }}</p>
                        <p class="text-sm text-slate-500">Published articles</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13H5a2 2 0 00-2 2v1a2 2 0 002 2h14a2 2 0 002-2v-1a2 2 0 00-2-2zm0-6H5a2 2 0 00-2 2v1a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2zM5 7h14"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Charter Services</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['charterServicesTotal']) }}</p>
                        <p class="text-sm text-slate-500">Active: {{ number_format($metrics['charterServicesActive']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-50">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Public Servants</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['publicServants']['total']) }}</p>
                        <p class="text-xs text-slate-500">
                            Male: {{ number_format($metrics['publicServants']['male']) }},
                            Female: {{ number_format($metrics['publicServants']['female']) }},
                            Other: {{ number_format($metrics['publicServants']['other']) }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c4.418 0 8-1.79 8-4V7a2 2 0 00-2-2h-3a2 2 0 00-2 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2V5a2 2 0 00-2-2H3a2 2 0 00-2 2v3c0 2.21 3.582 4 8 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Visits (30d)</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['visitsLast30Days']) }}</p>
                        <p class="text-xs text-slate-500">@if(!$analyticsVisitsAvailable) Analytics table missing @endif</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Unique Visitors (30d)</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['uniqueVisitorsLast30Days']) }}</p>
                        <p class="text-xs text-slate-500">@if(!$analyticsVisitsAvailable) Analytics table missing @endif</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50">
                        <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 10 4-18 3 10h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Pageviews (30d)</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['pageviewsLast30Days']) }}</p>
                        <p class="text-xs text-slate-500">@if(!$analyticsPageviewsAvailable) Analytics table missing @endif</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM3 12h18"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Users</p>
                        <p class="text-3xl font-bold text-slate-900">{{ number_format($metrics['usersTotal']) }}</p>
                        <p class="text-sm text-slate-500">Active (30d): {{ number_format($metrics['usersActiveLast30Days']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-pink-50">
                        <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20h10M12 12a4 4 0 100-8 4 4 0 000 8zm-9 8h2a2 2 0 012 2v1h6v-1a2 2 0 012-2h2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Recent Updates</h2>
                    <p class="text-sm text-slate-500">Latest activity across departments, services, and organizations.</p>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $recentUpdates->count() }} items</span>
            </div>
            <div class="mt-6 space-y-4">
                @forelse($recentUpdates as $item)
                    <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $item->title ?? 'Untitled' }}</p>
                            <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($item->updated_at ?? now())->diffForHumans() }}</p>
                        </div>
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            {{ $typeLabels[$item->type] ?? ucfirst($item->type ?? 'item') }}
                        </span>
                    </div>
                @empty
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-6 text-center text-sm text-slate-500">
                        No recent updates to display.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
