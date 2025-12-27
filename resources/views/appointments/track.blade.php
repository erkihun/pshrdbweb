<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.track_request') ?? 'Track Appointment' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('appointments.track.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="reference_code">
                            {{ __('common.labels.reference_code') }}
                        </label>
                        <input
                            id="reference_code"
                            name="reference_code"
                            type="text"
                            value="{{ $reference ?? old('reference_code') }}"
                            class="form-input"
                            required
                        >
                        @error('reference_code')
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            {{ __('common.actions.search') }}
                        </button>
                    </div>
                </form>
            </div>

            @if($appointment)
                <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-500">
                                {{ __('common.labels.reference_code') }}
                            </p>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $appointment->reference_code }}
                            </p>
                        </div>
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            {{ __('common.status.' . $appointment->status) }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $appointment->service->display_name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ ethiopian_date($appointment->slot->starts_at, 'dd MMMM yyyy') }} · {{ ethiopian_date($appointment->slot->starts_at, 'h:mm a') }} - {{ ethiopian_date($appointment->slot->ends_at, 'h:mm a') }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ __('common.labels.full_name') }}: {{ $appointment->full_name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ __('common.labels.phone') }}: {{ $appointment->phone }}
                        </p>
                        @if($appointment->email)
                            <p class="text-sm text-slate-500">
                                {{ __('common.labels.email') }}: {{ $appointment->email }}
                            </p>
                        @endif
                        @if($appointment->notes)
                            <div class="space-y-1 text-sm text-slate-500">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    {{ __('common.labels.details') }}
                                </p>
                                <p class="whitespace-pre-line text-sm text-slate-700">
                                    {{ $appointment->notes }}
                                </p>
                            </div>
                        @endif
                    </div>

                    @if(in_array($appointment->status, ['booked', 'confirmed'], true))
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-600">
                            <p class="font-semibold text-slate-800">
                                {{ __('common.actions.cancel') }} {{ __('common.labels.appointment') }}
                            </p>
                            <p class="mb-4 text-xs text-slate-500">
                                Provide a reason for cancelling (optional).
                            </p>
                            <form method="POST" action="{{ route('appointments.cancel', $appointment->reference_code) }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="reference_code" value="{{ $appointment->reference_code }}">
                                <div>
                                    <label class="text-sm font-semibold text-slate-700" for="reason">
                                        {{ __('common.labels.cancellation_reason') }}
                                    </label>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Provide a reason (optional)
                                    </p>
                                    <textarea
                                        id="reason"
                                        name="reason"
                                        rows="3"
                                        class="form-textarea"
                                    >{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-end gap-3">
                                    <a
                                        href="{{ route('appointments.index') }}"
                                        class="inline-flex items-center justify-center rounded-md border border-gray-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-gray-50"
                                    >
                                        {{ __('common.actions.back') }}
                                    </a>
                                    <button type="submit" class="btn-primary">
                                        {{ __('common.actions.cancel') }} {{ __('common.labels.appointment') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                            {{ __('common.messages.appointment_cannot_cancel') }}
                        </div>
                    @endif
                </div>
            @elseif(isset($reference))
                <div class="rounded-3xl border border-gray-200 bg-white p-6 text-sm text-slate-500">
                    {{ __('common.messages.appointment_not_found') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
