@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.appointment_details') }}</h1>
                <p class="text-sm text-slate-500">{{ $appointment->reference_code }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.labels.status') }}:</span>
                <span class="ml-2 rounded-full px-3 py-1 text-xs font-semibold uppercase {{ $appointment->status === 'cancelled' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-700' }}">
                    {{ __('common.status.' . $appointment->status) }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('common.labels.appointment_service') }}</h2>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $appointment->service->display_name }}</p>
                <p class="text-sm text-slate-500">{{ $appointment->service->display_description }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('common.labels.appointment_date') }}</h2>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ ethiopian_date($appointment->slot->starts_at, 'dd MMMM yyyy', 'Africa/Addis_Ababa', null, 'M d, Y', true) }}</p>
                <p class="text-sm text-slate-500">{{ ethiopian_date($appointment->slot->starts_at, 'h:mm a', 'Africa/Addis_Ababa', null, 'g:i A', true) }} - {{ ethiopian_date($appointment->slot->ends_at, 'h:mm a', 'Africa/Addis_Ababa', null, 'g:i A', true) }}</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ __('common.labels.full_name') }}</h2>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $appointment->full_name }}</p>
                <p class="text-sm text-slate-500">{{ $appointment->phone }}</p>
                @if ($appointment->email)
                    <p class="text-sm text-slate-500">{{ $appointment->email }}</p>
                @endif
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">{{ __('common.actions.update') }} {{ __('common.labels.status') }}</h2>
            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="space-y-4 pt-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="status">{{ __('common.labels.status') }}</label>
                    <select
                        id="status"
                        name="status"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                        @foreach (['booked', 'confirmed', 'cancelled', 'completed', 'no_show'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ __('common.status.' . $status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500" for="notes">{{ __('common.labels.admin_note') }}</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >{{ old('notes', $appointment->notes) }}</textarea>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-500">{{ __('common.labels.last_updated') }} {{ $appointment->updated_at->diffForHumans() }}</div>
                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        {{ __('common.actions.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

