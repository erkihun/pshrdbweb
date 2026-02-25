@extends('admin.layouts.app')

@section('content')
    @php
        $hasTable = $hasTable ?? true;
    @endphp

    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.optional_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('ui.subscriptions') }}</h1>
            <p class="text-sm text-slate-500">Manage newsletter subscribers and export their preferences.</p>
        </div>

        @unless($hasTable)
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-6 text-center shadow-sm">
                <p class="text-sm text-slate-500">Subscriber table is not available in this environment.</p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total subscribers</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($total ?? 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Active</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ number_format($active ?? 0) }}
                        <span class="text-xs text-slate-400">{{ $total > 0 ? round((($active ?? 0) / $total) * 100, 1) . '%' : '0%' }}</span>
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Verified</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ number_format($verified ?? 0) }}
                        <span class="text-xs text-slate-400">{{ $total > 0 ? round((($verified ?? 0) / $total) * 100, 1) . '%' : '0%' }}</span>
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Recent sign-ups</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($recent->count() ?? 0) }}</p>
                    <p class="text-xs text-slate-500 mt-1">latest 10</p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-900">Recent subscribers</h2>
                        <span class="text-sm text-slate-500">{{ $recent->count() }} shown</span>
                    </div>
                    <div class="mt-4 space-y-2 text-sm text-slate-700">
                        @forelse($recent as $subscriber)
                            <div class="flex flex-col rounded-lg border border-slate-100 px-3 py-2 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1 space-y-0.5">
                                    <p class="text-sm font-semibold text-slate-900">{{ $subscriber->email }}</p>
                                    <p class="text-xs text-slate-500">{{ $subscriber->locale ?? 'en' }} • {{ $subscriber->phone ?? '—' }}</p>
                                </div>
                                <div class="mt-2 flex items-center gap-3 text-xs uppercase tracking-wide text-slate-500 sm:mt-0">
                                    <span>{{ $subscriber->is_active ? 'Active' : 'Inactive' }}</span>
                                    <span>{{ $subscriber->verified_at ? 'Verified' : 'Unverified' }}</span>
                                    <span>{{ \Illuminate\Support\Carbon::parse($subscriber->created_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No subscribers yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Locale distribution</h2>
                    <div class="mt-4 space-y-2">
                        @foreach(($localeDistribution ?? collect())->take(5) as $locale => $count)
                            <div class="flex items-center justify-between text-sm text-slate-700">
                                <span class="uppercase tracking-wide text-slate-500">{{ $locale ?: 'unknown' }}</span>
                                <span>{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                        @if(empty($localeDistribution))
                            <p class="text-sm text-slate-500">No data available.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endunless
    </div>
@endsection
