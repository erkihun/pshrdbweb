@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('ui.resources_group') }}</p>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('ui.organizations') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.admin_organizations.list.description') }}</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <form method="GET" class="grid gap-2 sm:flex sm:items-center">
                    <label class="sr-only">{{ __('common.actions.search') ?? 'Search' }}</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="{{ __('common.admin_organizations.list.search_placeholder') }}"
                        class="w-full min-w-[220px] rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    <button type="submit" class="w-full rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto">{{ __('common.actions.filter') ?? 'Filter' }}</button>
                </form>
                <a href="{{ route('admin.organizations.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                    {{ __('common.admin_organizations.list.create') }}
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.admin_organizations.table.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.admin_organizations.table.code') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.admin_organizations.table.stats') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.admin_organizations.table.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.admin_organizations.table.created') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('common.admin_organizations.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($organizations as $organization)
                        <tr>
                            <td class="flex flex-col gap-1 px-6 py-4">
                                <a href="{{ route('admin.organizations.show', $organization) }}" class="text-sm font-semibold text-slate-900 hover:text-blue-600">{{ $organization->name }}</a>
                                <p class="text-xs text-slate-500">{{ __('common.admin_organizations.info.stats_recorded', ['count' => $organization->orgStats_count ?? 0]) }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $organization->code ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">{{ number_format($organization->orgStats_count ?? 0) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $organization->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ $organization->is_active ? __('common.admin_organizations.status.active') : __('common.admin_organizations.status.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $organization->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-slate-600">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.organizations.show', $organization) }}" class="text-blue-600 hover:text-blue-500">{{ __('common.admin_organizations.actions.view') }}</a>
                                    <a href="{{ route('admin.organizations.edit', $organization) }}" class="text-slate-600 hover:text-slate-900">{{ __('common.admin_organizations.actions.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.organizations.destroy', $organization) }}" onsubmit="return confirm('{{ __('common.admin_organizations.confirm.delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-500">{{ __('common.admin_organizations.actions.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                {{ __('common.admin_organizations.list.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between text-sm text-slate-500">
            <p>{{ __('common.admin_organizations.list.count', ['count' => $organizations->total()]) }}</p>
            {{ $organizations->links() }}
        </div>
    </div>
@endsection
