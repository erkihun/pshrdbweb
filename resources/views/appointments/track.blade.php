<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.labels.track_request') }}
            </h2>
            <p class="text-sm text-gray-500">{{ __('common.labels.appointment') }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('appointments.track.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-500" for="reference_code">{{ __('common.labels.reference_code') }}</label>
                        <input
                            id="reference_code"
                            name="reference_code"
                            type="text"
                            value="{{ old('reference_code', $reference ?? '') }}"
                            class="mt-2 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            required
                        >
                        @error('reference_code')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        {{ __('common.actions.search') }}
                    </button>
                </form>
            </div>

            @if ($appointment)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="text-sm text-slate-500">{{ __('common.labels.appointment_details') }}</div>
                    <p class="text-lg font-semibold text-slate-900">{{ $appointment->service->display_name }}</p>
                    <p class="text-sm text-slate-500">
                        {{ $appointment->slot->starts_at->format('M d, Y') }} · {{ $appointment->slot->starts_at->format('g:i A') }} - {{ $appointment->slot->ends_at->format('g:i A') }}
                    </p>
                    <p class="text-sm text-slate-500">{{ $appointment->full_name }} · {{ $appointment->phone }}</p>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('common.labels.status') }}</p>
                    <p class="text-sm font-semibold text-slate-900">{{ __('common.status.' . $appointment->status) }}</p>
                </div>
            @elseif ($reference)
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 text-sm text-rose-700">
                    {{ __('common.messages.appointment_not_found') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
