@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.tickets') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.nav.contact') }}</p>
            </div>
        </div>

        <form class="flex flex-wrap items-end gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="status">{{ __('common.labels.status') }}</label>
                <select
                    id="status"
                    name="status"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    <option value="open" @selected(request('status') === 'open')>{{ __('common.status.open') }}</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>{{ __('common.status.in_progress') }}</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>{{ __('common.status.resolved') }}</option>
                    <option value="closed" @selected(request('status') === 'closed')>{{ __('common.status.closed') }}</option>
                </select>
            </div>
            <div class="flex-1 min-w-[220px]">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400" for="q">{{ __('common.actions.search') }}</label>
                <input
                    id="q"
                    name="q"
                    type="text"
                    value="{{ request('q') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    placeholder="{{ __('common.labels.search_placeholder') }}"
                >
            </div>
            <div>
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
                        <th class="px-6 py-3">{{ __('common.labels.reference_code') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.full_name') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.subject') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.created_at') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.view') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $ticket->reference_code }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $ticket->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $ticket->subject }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ __('common.status.' . $ticket->status) }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ ethiopian_date($ticket->created_at, 'dd MMMM yyyy', 'Africa/Addis_Ababa', null, 'M d, Y', true) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.tickets.show', $ticket) }}"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                >
                                    {{ __('common.actions.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_tickets') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $tickets->links() }}
        </div>
    </div>
@endsection

