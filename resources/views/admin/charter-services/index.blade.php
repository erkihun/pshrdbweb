@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.citizen_charter.admin.heading') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.citizen_charter.admin.description') }}</p>
            </div>
            <a
                href="{{ route('admin.charter-services.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        <form method="GET" action="{{ route('admin.charter-services.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.filters.department') }}</label>
                    <select name="department" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200">
                        <option value="">{{ __('common.actions.choose') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(($filters['department'] ?? '') === $dept->id)>
                                {{ $dept->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.filters.status') }}</label>
                    <select name="status" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200">
                        <option value="">{{ __('common.labels.all') }}</option>
                        <option value="active" @selected(($filters['status'] ?? '') === 'active')>{{ __('common.status.active') }}</option>
                        <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>{{ __('common.status.inactive') }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wide text-slate-500">{{ __('common.citizen_charter.admin.filters.search') }}</label>
                    <input
                        type="search"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="{{ __('common.citizen_charter.admin.filters.search_placeholder') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                    >
                </div>
                <div class="flex items-end gap-3">
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        {{ __('common.actions.apply') }}
                    </button>
                    <a
                        href="{{ route('admin.charter-services.index') }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-400"
                    >
                        {{ __('common.actions.clear') }}
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">{{ __('common.citizen_charter.admin.columns.service') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('common.citizen_charter.admin.columns.department') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('common.citizen_charter.admin.columns.open_days') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('common.citizen_charter.admin.columns.status') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('common.citizen_charter.admin.columns.updated') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('common.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($services as $service)
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-slate-900">{{ $service->display_name }}</div>
                                    @if($service->name_en)
                                        <div class="text-xs text-slate-500">{{ $service->name_en }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $service->department?->display_name }}</td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $service->workingDaysLabel }}</div>
                                    <div class="text-xs text-slate-500">{{ $service->workingHoursLabel }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $service->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        <span class="h-2 w-2 rounded-full {{ $service->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $service->is_active ? __('common.status.active') : __('common.status.inactive') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ $service->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600">
                                        <a href="{{ route('admin.charter-services.show', $service) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ __('common.actions.view') }}
                                        </a>
                                        <a href="{{ route('admin.charter-services.edit', $service) }}" class="text-slate-600 hover:text-slate-900">
                                            {{ __('common.actions.edit') }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.charter-services.destroy', $service) }}" onsubmit="return confirm('{{ __('common.citizen_charter.admin.confirm.delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-700">
                                                {{ __('common.actions.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                                    {{ __('common.citizen_charter.admin.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($services->hasPages())
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 shadow-sm">
                {{ $services->links() }}
            </div>
        @endif
    </div>
@endsection
