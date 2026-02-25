@extends('admin.layouts.app')

@php
    use Illuminate\Support\Str;

    $dimensionGroups = $summary['by_dimension'] ?? [];
    $contactFields = collect([
        $organization->phone_primary,
        $organization->phone_secondary,
        $organization->email_primary,
        $organization->email_secondary,
        $organization->physical_address,
        $organization->city,
        $organization->region,
        $organization->country,
        $organization->website_url,
        $organization->map_embed_url,
    ]);

    $hasContactInfo = $contactFields->filter(fn ($value) => filled($value))->isNotEmpty();
@endphp

@section('content')
    <div class="space-y-6" x-data="{ tab: '{{ $activeTab ?? 'overview' }}' }">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.resources_group') }}</p>
                <h1 class="text-3xl font-semibold text-slate-900">{{ $organization->name }}</h1>
                <p class="text-sm text-slate-500">
                    @if($organization->code)
                        {{ __('common.admin_organizations.registered.with_code', ['code' => $organization->code]) }}
                    @endif
                    {{ __('common.admin_organizations.registered.at', ['time' => $organization->created_at->diffForHumans()]) }}
                    ? {{ $organization->is_active ? __('common.admin_organizations.status.active') : __('common.admin_organizations.status.inactive') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.organizations.edit', $organization) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                    {{ __('common.admin_organizations.actions.edit') }}
                </a>
                <a href="{{ route('admin.organizations.charts', ['organization' => $organization]) }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-500 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm hover:bg-blue-100">
                    {{ __('common.admin_organizations.actions.charts') }}
                </a>
                <a href="{{ route('admin.organizations.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                    {{ __('common.admin_organizations.actions.back') }}
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.cards.stats_recorded') }}</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $stats->count() }}</p>
                <p class="text-sm text-slate-500">{{ __('common.admin_organizations.cards.segments_total') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.cards.active_filters') }}</p>
                <p class="mt-2 text-sm text-slate-700">
                    @if($filterYear || $filterMonth)
                        @if($filterYear)
                            {{ __('common.admin_organizations.filters.year_label', ['year' => $filterYear]) }}
                        @endif
                        @if($filterMonth)
                            {{ __('common.admin_organizations.filters.month_label', ['month' => \Carbon\Carbon::create(null, $filterMonth)->format('F')]) }}
                        @endif
                    @else
                        {{ __('common.admin_organizations.cards.all_time') }}
                    @endif
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.cards.total_servants') }}</p>
                <div class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($summary['total']['total'] ?? 0) }}</div>
                <p class="text-sm text-slate-500">
                    {{ __('common.admin_organizations.cards.gender_summary', [
                        'male' => number_format($summary['total']['male'] ?? 0),
                        'female' => number_format($summary['total']['female'] ?? 0),
                        'other' => number_format($summary['total']['other'] ?? 0),
                    ]) }}
                </p>
            </div>
        </div>

        @if($hasContactInfo)
            <div class="grid gap-5 lg:grid-cols-[1.25fr,0.75fr]">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.card_title') }}</p>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('common.admin_organizations.contact.card_subtitle') }}</h2>
                        </div>
                    </div>
                    <div class="mt-4 space-y-4 text-sm text-slate-700">
                        @if($organization->physical_address)
                            <p class="space-y-1">
                                <span class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.address') }}</span>
                                <span class="block font-semibold text-slate-900">
                                    {{ $organization->physical_address }}
                                    @if($organization->city), {{ $organization->city }}@endif
                                    @if($organization->region), {{ $organization->region }}@endif
                                    @if($organization->country), {{ $organization->country }}@endif
                                </span>
                            </p>
                        @endif
                    <div class="grid gap-3 sm:grid-cols-2">
                        @if($organization->phone_primary)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.primary_phone') }}</p>
                                    <a href="tel:{{ preg_replace('/\\D+/', '', $organization->phone_primary) }}" class="font-semibold text-slate-900 hover:text-blue-600">
                                        {{ $organization->phone_primary }}
                                    </a>
                                </div>
                            @endif
                            @if($organization->phone_secondary)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.secondary_phone') }}</p>
                                    <a href="tel:{{ preg_replace('/\\D+/', '', $organization->phone_secondary) }}" class="font-semibold text-slate-900 hover:text-blue-600">
                                        {{ $organization->phone_secondary }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @if($organization->email_primary)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.primary_email') }}</p>
                                    <a href="mailto:{{ $organization->email_primary }}" class="font-semibold text-slate-900 hover:text-blue-600">
                                        {{ $organization->email_primary }}
                                    </a>
                                </div>
                            @endif
                            @if($organization->email_secondary)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.secondary_email') }}</p>
                                    <a href="mailto:{{ $organization->email_secondary }}" class="font-semibold text-slate-900 hover:text-blue-600">
                                        {{ $organization->email_secondary }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        @if($organization->website_url)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.website') }}</p>
                                <a href="{{ $organization->website_url }}" target="_blank" rel="nofollow noopener" class="font-semibold text-blue-600 hover:underline">
                                    {{ $organization->website_url }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @if($organization->map_embed_url)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.contact.map') }}</p>
                        <div class="mt-3 h-48 overflow-hidden rounded-xl border border-slate-200">
                            <iframe
                                src="{{ $organization->map_embed_url }}"
                                class="h-full w-full border-0"
                                loading="lazy"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap gap-2 border-b border-slate-200 px-4 py-3">
                <button
                    type="button"
                    @click="tab = 'overview'"
                    :class="tab === 'overview' ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition"
                >
                    {{ __('common.admin_organizations.tabs.overview') }}
                </button>
                <button
                    type="button"
                    @click="tab = 'stats'"
                    :class="tab === 'stats' ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition"
                >
                    {{ __('common.admin_organizations.tabs.stats') }}
                </button>
            </div>

            <div class="px-4 py-6" x-cloak>
                <div x-show="tab === 'overview'">
                    <div class="grid gap-4 sm:grid-cols-4">
                        @foreach(['male', 'female', 'other', 'total'] as $field)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-center">
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.metrics.' . $field) }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($summary['total'][$field] ?? 0) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.show.code') }}</p>
                            <p class="mt-1 text-sm text-slate-700">{{ $organization->code ?? __('common.labels.not_available') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.show.created') }}</p>
                            <p class="mt-1 text-sm text-slate-700">{{ $organization->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.show.updated') }}</p>
                            <p class="mt-1 text-sm text-slate-700">{{ $organization->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>


                <div x-show="tab === 'stats'">
                    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),360px]">
                        <div class="space-y-5">
                            <form method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.admin_organizations.filters.heading') }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['male', 'female', 'other', 'total'] as $metric)
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                                {{ __('common.admin_organizations.metrics.' . $metric) }}: {{ number_format($summary['total'][$metric] ?? 0) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.filters.year') }}</label>
                                        <input
                                            type="number"
                                            name="year"
                                            min="2000"
                                            max="2100"
                                            value="{{ $filterYear }}"
                                            class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.filters.month') }}</label>
                                        <select name="month" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value=""></option>
                                            @foreach(range(1, 12) as $month)
                                                <option value="{{ $month }}" @selected($filterMonth == $month)>{{ \Carbon\Carbon::create()->month($month)->locale(app()->getLocale())->translatedFormat('F') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="tab" value="stats">
                                <div class="mt-4 flex flex-wrap items-center gap-2">
                                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow-sm hover:bg-blue-500">{{ __('common.admin_organizations.filters.apply') }}</button>
                                    <a href="{{ route('admin.organizations.show', ['organization' => $organization, 'tab' => 'stats']) }}" class="text-xs font-semibold uppercase tracking-wide text-slate-500 hover:text-slate-700">{{ __('common.admin_organizations.filters.reset') }}</a>
                                </div>
                            </form>

                        </div>

                        <div class="flex flex-col gap-4 xl:sticky xl:top-6 xl:self-start">
                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <h2 class="text-sm font-semibold text-slate-900">{{ __('common.admin_organizations.segments.add_new') }}</h2>
                                <form method="POST" action="{{ route('admin.organizations.stats.store', $organization) }}" class="mt-4 space-y-4">
                                    @csrf
                                    <div class="space-y-2">
                                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.dimension') }}</label>
                                        <input
                                            type="text"
                                            name="dimension"
                                            value="{{ old('dimension') }}"
                                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                        @error('dimension')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.segment') }}</label>
                                        <input
                                            type="text"
                                            name="segment"
                                            value="{{ old('segment') }}"
                                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                        @error('segment')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                                    </div>
                                    <input type="hidden" name="other" value="0">
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        @foreach(['male', 'female'] as $field)
                                            <div class="space-y-2">
                                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.metrics.' . $field) }}</label>
                                                <input
                                                    type="number"
                                                    name="{{ $field }}"
                                                    value="{{ old($field, 0) }}"
                                                    min="0"
                                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                                    required
                                                >
                                                @error($field)<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.year') }}</label>
                                            <input
                                                type="number"
                                                name="year"
                                                value="{{ old('year') }}"
                                                min="2000"
                                                max="2100"
                                                class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                            >
                                            @error('year')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.month') }}</label>
                                            <select
                                                name="month"
                                                class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                            >
                                                <option value=""></option>
                                                @foreach(range(1, 12) as $month)
                                                    <option value="{{ $month }}" @selected(old('month') == $month)>{{ \Carbon\Carbon::create()->month($month)->locale(app()->getLocale())->translatedFormat('F') }}</option>
                                                @endforeach
                                            </select>
                                            @error('month')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                                            {{ __('common.admin_organizations.segments.submit') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="space-y-4">
                                @if(count($dimensionGroups))
                                    @foreach($dimensionGroups as $dimension => $dimensionSummary)
                                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                            <div class="flex flex-wrap items-center justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">{{ Str::headline($dimension) }}</p>
                                                    <p class="text-xs text-slate-400">{{ __('common.admin_organizations.segments.totals_label') }}</p>
                                                </div>
                                                <div class="flex gap-3">
                                                    @foreach(['male', 'female', 'other', 'total'] as $metric)
                                                        <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                            {{ __('common.admin_organizations.segments.metrics.' . $metric) }} {{ number_format($dimensionSummary[$metric] ?? 0) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="mt-4 space-y-3">
                                                @foreach($dimensionSummary['segments'] as $segment)
                                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                                            <div>
                                                                <p class="text-sm font-semibold text-slate-900">{{ $segment['segment'] }}</p>
                                                                <p class="text-xs text-slate-500">
                                                                    @if($segment['year'])
                                                                        {{ __('common.admin_organizations.filters.year_label', ['year' => $segment['year']]) }}
                                                                    @else
                                                                        {{ __('common.admin_organizations.filters.year_all') }}
                                                                    @endif
                                                                    @if($segment['month'])
                                                                        {{ __('common.admin_organizations.filters.month_label', ['month' => \Carbon\Carbon::create()->month($segment['month'])->locale(app()->getLocale())->translatedFormat('F')]) }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-wide text-slate-600">
                                                                <span>{{ __('common.admin_organizations.segments.metrics.male') }} {{ number_format($segment['male']) }}</span>
                                                                <span>{{ __('common.admin_organizations.segments.metrics.female') }} {{ number_format($segment['female']) }}</span>
                                                                <span>{{ __('common.admin_organizations.segments.metrics.other') }} {{ number_format($segment['other']) }}</span>
                                                                <span>{{ __('common.admin_organizations.segments.metrics.total') }} {{ number_format(($segment['male'] ?? 0) + ($segment['female'] ?? 0) + ($segment['other'] ?? 0)) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3 flex items-center justify-end gap-3">
                                                            <a href="{{ route('admin.organizations.stats.edit', [$organization, $segment['id']]) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-500">{{ __('common.admin_organizations.actions.edit') }}</a>
                                                            <form method="POST" action="{{ route('admin.organizations.stats.destroy', [$organization, $segment['id']]) }}" class="m-0" onsubmit="return confirm('{{ __('common.admin_organizations.confirm.delete_segment') }}');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">{{ __('common.admin_organizations.actions.delete') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">{{ __('common.admin_organizations.segments.no_stats') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
