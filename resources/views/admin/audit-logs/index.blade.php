@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.audit_logs') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.users') }} Â· {{ __('common.actions.filter') }}</p>
            </div>
        </div>

        <form class="flex flex-wrap gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="min-w-[160px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="from">From</label>
                <input
                    id="from"
                    name="from"
                    type="date"
                    value="{{ request('from') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
            </div>
            <div class="min-w-[160px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="to">To</label>
                <input
                    id="to"
                    name="to"
                    type="date"
                    value="{{ request('to') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
            </div>
            <div class="min-w-[200px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="user_id">{{ __('common.labels.users') }}</label>
                <select
                    id="user_id"
                    name="user_id"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[200px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="action">Action</label>
                <select
                    id="action"
                    name="action"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[200px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="entity_type">Entity</label>
                <select
                    id="entity_type"
                    name="entity_type"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($entityTypes as $entityType)
                        <option value="{{ $entityType }}" @selected(request('entity_type') === $entityType)>{{ $entityType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[220px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="q">{{ __('common.actions.search') }}</label>
                <input
                    id="q"
                    name="q"
                    type="text"
                    value="{{ request('q') }}"
                    placeholder="reference/title/slug"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
            </div>
            <div class="flex items-end">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.filter') }}
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">{{ __('common.labels.users') }}</th>
                        <th class="px-6 py-3">Action</th>
                        <th class="px-6 py-3">Entity</th>
                        <th class="px-6 py-3">Reference</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($auditLogs as $log)
                        <tr>
                            <td class="px-6 py-4 text-slate-500">{{ ethiopian_date($log->created_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'Y-m-d H:i', true) }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $log->user?->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $log->action }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $log->entity_type }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $log->metadata['reference_code'] ?? $log->metadata['title'] ?? $log->metadata['slug'] ?? 'â€”' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.audit-logs.show', $log) }}"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                >
                                    {{ __('common.actions.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_audit_logs') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $auditLogs->links() }}
        </div>
    </div>
@endsection

