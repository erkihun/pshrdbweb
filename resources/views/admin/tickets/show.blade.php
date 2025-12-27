@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.tickets') }}</h1>
                <p class="text-sm text-slate-500">{{ $ticket->reference_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('admin.tickets.index') }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:text-slate-900"
                >
                    {{ __('common.actions.back') }}
                </a>
                <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:text-rose-700"
                        onclick="return confirm('Delete this ticket?')"
                    >
                        {{ __('common.actions.delete') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.message') }}</h2>
                    <p class="mt-3 text-sm text-slate-600 whitespace-pre-line">{{ $ticket->message }}</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.reply') }}</h2>
                    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="mt-4 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                            <select
                                id="status"
                                name="status"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                                <option value="open" @selected(old('status', $ticket->status) === 'open')>{{ __('common.status.open') }}</option>
                                <option value="in_progress" @selected(old('status', $ticket->status) === 'in_progress')>{{ __('common.status.in_progress') }}</option>
                                <option value="resolved" @selected(old('status', $ticket->status) === 'resolved')>{{ __('common.status.resolved') }}</option>
                                <option value="closed" @selected(old('status', $ticket->status) === 'closed')>{{ __('common.status.closed') }}</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="admin_reply">{{ __('common.labels.admin_reply') }}</label>
                            <textarea
                                id="admin_reply"
                                name="admin_reply"
                                rows="6"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                            >
                                {{ __('common.actions.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.details') }}</h2>
                    <dl class="mt-4 space-y-4 text-sm text-slate-600">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.reference_code') }}</dt>
                            <dd class="mt-1 text-slate-800">{{ $ticket->reference_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.full_name') }}</dt>
                            <dd class="mt-1 text-slate-800">{{ $ticket->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.email') }}</dt>
                            <dd class="mt-1 text-slate-800">{{ $ticket->email }}</dd>
                        </div>
                        @if ($ticket->phone)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.phone') }}</dt>
                                <dd class="mt-1 text-slate-800">{{ $ticket->phone }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.subject') }}</dt>
                            <dd class="mt-1 text-slate-800">{{ $ticket->subject }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</dt>
                            <dd class="mt-1 text-slate-800">{{ __('common.status.' . $ticket->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.created_at') }}</dt>
                            <dd class="mt-1 text-slate-800">{{ ethiopian_date($ticket->created_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) }}</dd>
                        </div>
                        @if ($ticket->replied_at)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.replied_at') }}</dt>
                                <dd class="mt-1 text-slate-800">{{ ethiopian_date($ticket->replied_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) }}</dd>
                            </div>
                        @endif
                        @if ($ticket->repliedBy)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.replied_by') }}</dt>
                                <dd class="mt-1 text-slate-800">{{ $ticket->repliedBy->name }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection

