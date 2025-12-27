@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.appointments') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.appointments') }} overview.</p>
            </div>
        </div>

        <form method="GET" class="grid gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="service-filter">{{ __('common.labels.appointment_service') }}</label>
                <select
                    id="service-filter"
                    name="service_id"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="status-filter">{{ __('common.labels.status') }}</label>
                <select
                    id="status-filter"
                    name="status"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    <option value="booked" @selected(request('status') === 'booked')>{{ __('common.status.booked') }}</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>{{ __('common.status.confirmed') }}</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>{{ __('common.status.cancelled') }}</option>
                    <option value="completed" @selected(request('status') === 'completed')>{{ __('common.status.completed') }}</option>
                    <option value="no_show" @selected(request('status') === 'no_show')>{{ __('common.status.no_show') }}</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="reference">{{ __('common.labels.reference_code') }}</label>
                <input
                    id="reference"
                    name="reference"
                    type="text"
                    value="{{ request('reference') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    placeholder="{{ __('common.labels.reference_code') }}"
                >
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="search">{{ __('common.actions.search') }}</label>
                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    placeholder="{{ __('common.labels.search_placeholder') }}"
                >
            </div>
            <div class="md:col-span-4 flex justify-end">
                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800"
                    >
                        {{ __('common.actions.filter') }}
                    </button>
                    <a
                        href="{{ route('admin.appointments.index') }}"
                        class="text-xs font-semibold uppercase tracking-wide text-slate-500 hover:text-slate-900"
                    >
                        {{ __('common.actions.clear') ?? 'Clear' }}
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.reference_code') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_service') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_date') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.full_name') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.phone') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td class="px-6 py-4 text-slate-900 font-medium">{{ $appointment->reference_code }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $appointment->service->display_name }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ ethiopian_date($appointment->slot->starts_at, 'dd MMMM yyyy', 'Africa/Addis_Ababa', null, 'M d, Y', true) }}
                                <br>
                                <span class="text-xs text-slate-400">
                                    {{ ethiopian_date($appointment->slot->starts_at, 'h:mm a', 'Africa/Addis_Ababa', null, 'g:i A', true) }} - {{ ethiopian_date($appointment->slot->ends_at, 'h:mm a', 'Africa/Addis_Ababa', null, 'g:i A', true) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $appointment->status === 'cancelled' ? 'text-rose-600' : 'text-slate-900' }}">
                                    {{ __('common.status.' . $appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $appointment->full_name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $appointment->phone }}</td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.appointments.show', $appointment) }}"
                                    class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                >
                                    {{ __('common.actions.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_appointments') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $appointments->links() }}
        </div>
    </div>
@endsection

