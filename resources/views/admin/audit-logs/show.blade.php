@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.audit_logs') }}</h1>
                <p class="text-sm text-slate-500">{{ $auditLog->action }}</p>
            </div>
            <a
                href="{{ route('admin.audit-logs.index') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.back') }}
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.users') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $auditLog->user?->name ?? 'Guest' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Action</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $auditLog->action }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Entity</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $auditLog->entity_type }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Entity ID</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $auditLog->entity_id ?? 'â€”' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">IP</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ $auditLog->ip_address ?? 'â€”' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Created</dt>
                    <dd class="mt-1 font-medium text-slate-800">{{ ethiopian_date($auditLog->created_at, 'dd MMMM yyyy h:mm:ss a', 'Africa/Addis_Ababa', null, 'Y-m-d H:i:s', true) }}</dd>
                </div>
            </dl>

            @if ($auditLog->user_agent)
                <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                    <h2 class="text-sm font-semibold text-slate-900">User Agent</h2>
                    <p class="mt-2 break-words">{{ $auditLog->user_agent }}</p>
                </div>
            @endif

            @if (!empty($auditLog->metadata))
                <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                    <h2 class="text-sm font-semibold text-slate-900">Metadata</h2>
                    <pre class="mt-2 rounded-lg bg-slate-950 p-4 text-xs text-slate-100">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif
        </div>
    </div>
@endsection

