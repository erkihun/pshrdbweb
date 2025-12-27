@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.appointment_slots') }}</h1>
                <p class="text-sm text-slate-500">{{ __('common.labels.appointment_slots') }}</p>
            </div>
            <a
                href="{{ route('admin.appointment-slots.create') }}"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.create') }}
            </a>
        </div>

        <form method="GET" class="flex flex-wrap items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="service-filter">{{ __('common.labels.appointment_service') }}</label>
                <select
                    id="service-filter"
                    name="service_id"
                    class="mt-2 w-48 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.labels.all') }}</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-slate-800"
            >
                {{ __('common.actions.filter') }}
            </button>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_service') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_date') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_time') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.appointment_slots') }}</th>
                        <th class="px-6 py-3">{{ __('common.labels.status') }}</th>
                        <th class="px-6 py-3 text-right">{{ __('common.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($slots as $slot)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $slot->service?->display_name ?? __('common.labels.appointment_service') }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ ethiopian_date($slot->starts_at, 'dd MMMM yyyy', 'Africa/Addis_Ababa', null, 'M d, Y', true) }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ ethiopian_date($slot->starts_at, 'h:mm a', 'Africa/Addis_Ababa', null, 'g:i A', true) }} - {{ ethiopian_date($slot->ends_at, 'h:mm a', 'Africa/Addis_Ababa', null, 'g:i A', true) }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $slot->capacity }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $slot->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $slot->is_active ? __('common.status.active') : __('common.status.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a
                                        href="{{ route('admin.appointment-slots.edit', $slot) }}"
                                        class="text-sm font-medium text-slate-600 hover:text-slate-900"
                                    >
                                        {{ __('common.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.appointment-slots.destroy', $slot) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-sm font-medium text-rose-600 hover:text-rose-700"
                                            onclick="return confirm('Delete this slot?')"
                                        >
                                            {{ __('common.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                {{ __('common.messages.no_appointments') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $slots->links() }}
        </div>
    </div>
@endsection

