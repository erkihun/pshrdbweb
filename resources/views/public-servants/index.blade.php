@extends('layouts.public')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-6xl space-y-6 px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('ui.public_servants') }}</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Public servant statistics</h1>
                    <p class="text-sm text-slate-500">Browse the latest workforce counts for each organization.</p>
                </div>
                <div class="text-sm text-slate-500">
                    Updated {{ now()->format('M d, Y \\a\\t H:i') }}
                </div>
            </div>

            <form method="GET" class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:grid-cols-3">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Year</label>
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
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Month</label>
                    <select
                        name="month"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value=""></option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" @selected($filterMonth == $month)>{{ \DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">Apply</button>
                    <a href="{{ route('public-servants.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Clear</a>
                </div>
            </form>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach(['male', 'female', 'other', 'total'] as $metric)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-slate-400">{{ ucfirst($metric) }}</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($summary['total'][$metric] ?? 0) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Organization</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Male</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Female</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Other</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Total</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Period</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($organizations as $record)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $record['organization']->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $record['organization']->code ?? 'Code N/A' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['male']) }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['female']) }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['other']) }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($record['total']) }}</td>
                                <td class="px-5 py-4 text-right text-xs text-slate-500">
                                    {{ $record['year'] ?? 'Year: All' }}
                                    @if($record['month'])
                                        • {{ \DateTime::createFromFormat('!m', $record['month'])->format('F') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500">No statistics recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
