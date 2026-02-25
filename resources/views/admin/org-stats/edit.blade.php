@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.resources_group') }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.admin_org_stats.edit_title', ['name' => $organization->name]) }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.admin_org_stats.edit_description') }}</p>
        </div>
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr),360px]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.organizations.stats.update', [$organization, $stat]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.dimension') }}</label>
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
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.segment') }}</label>
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
                                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.metrics.' . $field) }}</label>
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
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.year') }}</label>
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
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.segments.field.month') }}</label>
                            <select name="month" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value=""></option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" @selected(old('month', $stat->month) == $month)>{{ \Carbon\Carbon::create()->month($month)->locale(app()->getLocale())->translatedFormat('F') }}</option>
                                @endforeach
                            </select>
                            @error('month')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex justify-between gap-3">
                        <a href="{{ route('admin.organizations.show', $organization) }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">{{ __('common.actions.cancel') }}</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">{{ __('common.actions.update') }}</button>
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
                        </div>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach(['male', 'female', 'other'] as $field)
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.admin_organizations.metrics.' . $field) }}</label>
                                    <input
                                        type="number"
                                        name="{{ $field }}"
                                        min="0"
                                        value="{{ old($field, 0) }}"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    >
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
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                                {{ __('common.admin_organizations.segments.submit') }}
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.admin_org_stats.saved_segments') }}</h2>
                    <div class="mt-4 space-y-3">
                        @forelse($savedSegments as $savedSegment)
                            <div class="rounded-xl border {{ $savedSegment->id === $stat->id ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-slate-50' }} p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $savedSegment->segment }}</p>
                                        <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::headline($savedSegment->dimension) }}</p>
                                    </div>
                                    <p class="text-[11px] uppercase tracking-wide text-slate-500">
                                        {{ number_format(($savedSegment->male ?? 0) + ($savedSegment->female ?? 0) + ($savedSegment->other ?? 0)) }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.organizations.stats.edit', [$organization, $savedSegment]) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-500">{{ __('common.admin_organizations.actions.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.organizations.stats.destroy', [$organization, $savedSegment]) }}" class="m-0" onsubmit="return confirm('{{ __('common.admin_organizations.confirm.delete_segment') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-500">{{ __('common.admin_organizations.actions.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-500">{{ __('common.admin_organizations.segments.no_stats') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
