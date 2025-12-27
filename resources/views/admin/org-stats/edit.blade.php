@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.resources_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Edit statistic for {{ $organization->name }}</h1>
            <p class="text-sm text-slate-500">Adjust the numbers and time frame for this segment.</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.organizations.stats.update', [$organization, $stat]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-4">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Dimension</label>
                        <input
                            type="text"
                            name="dimension"
                            value="{{ old('dimension', $stat->dimension) }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                        @error('dimension')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Segment</label>
                        <input
                            type="text"
                            name="segment"
                            value="{{ old('segment', $stat->segment) }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                        @error('segment')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach(['male', 'female', 'other'] as $field)
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ ucfirst($field) }}</label>
                            <input
                                type="number"
                                name="{{ $field }}"
                                min="0"
                                value="{{ old($field, $stat->{$field}) }}"
                                class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                required
                            >
                            @error($field)<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Year</label>
                        <input
                            type="number"
                            name="year"
                            min="2000"
                            max="2100"
                            value="{{ old('year', $stat->year) }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('year')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Month</label>
                        <select name="month" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value=""></option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" @selected(old('month', $stat->month) == $month)>{{ \DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endforeach
                        </select>
                        @error('month')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-between gap-3">
                    <a href="{{ route('admin.organizations.show', $organization) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">{{ __('common.actions.cancel') ?? 'Cancel' }}</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">{{ __('common.actions.update') ?? 'Update' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
