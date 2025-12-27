<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.actions.create') }} {{ __('common.labels.appointment') }}
        </h2>
    </x-slot>

    @php
        $selectedService = $selectedService ?? $services->first();
        $slotsForSelected = $slots ?? collect();
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="rounded-3xl border border-gray-100 bg-gradient-to-br from-blue-600 to-indigo-700 p-8 text-white shadow-xl">
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-blue-200">
                        {{ __('common.nav.appointments') }}
                    </p>
                    <h1 class="text-3xl font-bold leading-tight sm:text-4xl">
                        {{ __('common.actions.create') }} {{ __('common.labels.appointment') }}
                    </h1>
                    <p class="text-base text-white/80">
                        Select the public service you need, pick a slot, and share your contact details to securely reserve an appointment.
                    </p>
                </div>
            </section>

            <div class="space-y-6 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                @if($selectedService)
                    <form
                        id="appointment-create"
                        class="space-y-6"
                        method="POST"
                        action="{{ route('appointments.book', $selectedService) }}"
                    >
                        @csrf

                        <div>
                            <label class="text-sm font-semibold text-slate-700" for="service_id">
                                {{ __('common.labels.appointment_service') }}
                            </label>
                            <select
                                id="service_id"
                                class="form-input"
                            >
                                @foreach($services as $service)
                                    <option
                                        value="{{ $service->slug }}"
                                        @selected($selectedService->id === $service->id)
                                    >
                                        {{ $service->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ __('common.labels.appointment_service_duration') }}:
                                {{ $selectedService?->duration_minutes ?? __('common.messages.no_appointments') }} mins
                            </p>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-900">
                                    {{ __('common.labels.appointment_slots') }}
                                </span>
                                <a
                                    href="{{ route('appointments.show', $selectedService) }}"
                                    class="text-xs font-semibold text-blue-600 transition hover:text-blue-800"
                                >
                                    {{ __('common.actions.view') }}
                                </a>
                            </div>

                            @if($slotsForSelected->isEmpty())
                                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-4 text-sm text-slate-500">
                                    {{ __('common.messages.no_appointments') }}
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($slotsForSelected as $slot)
                                        <label
                                            class="relative flex cursor-pointer items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 transition hover:border-blue-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200"
                                        >
                                            <input
                                                type="radio"
                                                name="appointment_slot_id"
                                                value="{{ $slot->id }}"
                                                class="peer sr-only"
                                                @checked(old('appointment_slot_id') === $slot->id)
                                                @disabled($slot->availableSeats === 0)
                                            >
                                            <div class="flex-1 space-y-1">
                                                <p class="text-sm font-semibold text-slate-900">
                                                    {{ ethiopian_date($slot->starts_at, 'dd MMMM yyyy') }}
                                                </p>
                                                <p class="text-sm text-slate-500">
                                                    {{ ethiopian_date($slot->starts_at, 'h:mm a') }} - {{ ethiopian_date($slot->ends_at, 'h:mm a') }}
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $slot->availableSeats }} seats remaining · {{ $slot->capacity }} total
                                                </p>
                                            </div>
                                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                {{ $slot->availableSeats > 0 ? __('common.messages.available') : 'Full' }}
                                            </span>
                                            <div class="pointer-events-none absolute inset-0 rounded-2xl border-2 border-transparent transition peer-checked:border-blue-500"></div>
                                        </label>
                                    @endforeach
                                    @error('appointment_slot_id')
                                        <p class="text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700" for="full_name">
                                    {{ __('common.labels.full_name') }}
                                </label>
                                <input id="full_name" name="full_name" value="{{ old('full_name') }}" class="form-input" required>
                                @error('full_name')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700" for="phone">
                                    {{ __('common.labels.phone') }}
                                </label>
                                <input id="phone" name="phone" value="{{ old('phone') }}" class="form-input" required>
                                @error('phone')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700" for="email">
                                    {{ __('common.labels.email') }}
                                </label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-input">
                                @error('email')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700" for="notes">
                                    {{ __('common.labels.message') }}
                                </label>
                                <textarea id="notes" name="notes" rows="4" class="form-textarea">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                {{ __('common.actions.submit') }}
                            </button>
                        </div>
                    </form>
                @else
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-slate-500">
                        {{ __('common.messages.no_appointments') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        (() => {
            const serviceSelect = document.getElementById('service_id');
            if (!serviceSelect) {
                return;
            }

            serviceSelect.addEventListener('change', () => {
                const slug = serviceSelect.value;
                if (!slug) {
                    return;
                }
                const url = new URL(window.location.href);
                url.searchParams.set('service', slug);
                window.location.href = url.toString();
            });
        })();
    </script>
</x-app-layout>
