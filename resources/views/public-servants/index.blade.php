@extends('layouts.public')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-6xl space-y-6 px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('ui.public_servants') }}</p>
                    <h1 class="text-3xl font-semibold text-slate-900">{{ __('public-servants.title') }}</h1>
                    <p class="text-sm text-slate-500">{{ __('public-servants.description') }}</p>
                </div>
                <div class="text-sm text-slate-500">
                    {{ __('public-servants.updated', ['time' => now()->format('M d, Y \\a\\t H:i')]) }}
                </div>
            </div>

            <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('public-servants.filters.year') }}</label>
                    <input
                        type="number"
                        name="year"
                        min="2000"
                        max="2100"
                        value="{{ $filterYear }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('public-servants.filters.month') }}</label>
                    <select
                        name="month"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">{{ __('public-servants.filters.month_placeholder') }}</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" @selected($filterMonth == $month)>{{ \DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">{{ __('public-servants.filters.apply') }}</button>
                    <a href="{{ route('public-servants.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">{{ __('public-servants.filters.clear') }}</a>
                </div>
            </form>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach(['male', 'female', 'other', 'total'] as $metric)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('public-servants.summary.' . $metric) }}</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($summary['total'][$metric] ?? 0) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('public-servants.table.organization') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('public-servants.table.male') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('public-servants.table.female') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('public-servants.table.other') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('public-servants.table.total') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('public-servants.table.period') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($organizations as $record)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $record['organization']->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $record['organization']->code ?? __('public-servants.code_na') }}</p>
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['male']) }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['female']) }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['other']) }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['total']) }}</td>
                                <td class="px-5 py-4 text-right text-xs text-slate-500">
                                    {{ $record['year'] ?? __('public-servants.year_all') }}
                                    @if($record['month'])
                                        • {{ \DateTime::createFromFormat('!m', $record['month'])->format('F') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500">{{ __('public-servants.no_statistics') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
