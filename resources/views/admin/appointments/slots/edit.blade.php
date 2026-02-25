@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.actions.edit') }} {{ __('common.labels.appointment_slot') }}</h1>
            <p class="text-sm text-slate-500">
                {{ $appointmentSlot->service?->display_name ?? __('common.labels.appointment_service') }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.appointment-slots.update', $appointmentSlot) }}">
                @csrf
                @method('PUT')

                @include('admin.appointments.slots._form', ['appointmentSlot' => $appointmentSlot])

                <div class="mt-6 flex justify-end">
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

