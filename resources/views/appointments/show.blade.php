<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $appointmentService->display_name }}
            </h2>
            <p class="text-sm text-gray-500 flex items-center gap-2">
                {{ $officeHoursService->summary() }}
                <span
                    aria-label="Office hours indicator"
                    class="h-2 w-2 rounded-full {{ $officeHoursService->isOpen() ? 'bg-emerald-500' : 'bg-rose-500' }}"
                ></span>
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <p class="text-sm text-gray-600">{{ $appointmentService->display_description }}</p>
                        <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <span>{{ $appointmentService->duration_minutes }} {{ __('common.labels.appointment_service_duration') }}</span>
                            <span class="text-emerald-600">{{ $appointmentService->is_active ? __('common.status.active') : __('common.status.inactive') }}</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('common.labels.appointment_slots') }}</h3>
                            @if ($slots->isNotEmpty())
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $slots->count() }} slots</span>
                            @endif
                        </div>

                        <div class="mt-4 grid gap-4">
                            @forelse ($slots as $slot)
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $slot->starts_at->format('M d, Y') }}</p>
                                            <p class="text-xs uppercase tracking-wide text-gray-500">
                                                {{ $slot->starts_at->format('g:i A') }} - {{ $slot->ends_at->format('g:i A') }}
                                            </p>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700">
                                            {{ $slot->available_seats }} {{ __('common.messages.available') }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ __('common.labels.appointment_slots') }}: {{ $slot->capacity }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">{{ __('common.messages.no_appointments') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('common.actions.book') }} {{ __('common.nav.appointments') }}</h3>
                        <p class="text-sm text-gray-500">{{ $officeHoursService->isOpen() ? __('common.messages.available') : __('common.messages.office_hours_required', ['hours' => $officeHoursService->summary()]) }}</p>

                        <form
                            method="POST"
                            action="{{ route('appointments.book', $appointmentService) }}"
                            class="mt-6 space-y-4"
                        >
                            @csrf

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-500" for="appointment_slot_id">{{ __('common.labels.appointment_slots') }}</label>
                                <select
                                    id="appointment_slot_id"
                                    name="appointment_slot_id"
                                    class="mt-2 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                    required
                                >
                                    <option value="">{{ __('common.actions.choose') }}</option>
                                    @foreach ($slots as $slot)
                                        <option value="{{ $slot->id }}">
                                            {{ $slot->starts_at->format('M d, Y') }} · {{ $slot->starts_at->format('g:i A') }} - {{ $slot->ends_at->format('g:i A') }}
                                            ({{ $slot->available_seats }} {{ __('common.messages.available') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_slot_id')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-500" for="full_name">{{ __('common.labels.full_name') }}</label>
                                <input
                                    id="full_name"
                                    name="full_name"
                                    type="text"
                                    value="{{ old('full_name') }}"
                                    class="mt-2 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                    required
                                >
                                @error('full_name')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-500" for="phone">{{ __('common.labels.phone') }}</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    value="{{ old('phone') }}"
                                    class="mt-2 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                    required
                                >
                                @error('phone')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-500" for="email">{{ __('common.labels.email') }}</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    class="mt-2 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                >
                                @error('email')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-500" for="notes">{{ __('common.labels.details') }}</label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    class="mt-2 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                >{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="mt-2 w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-70"
                                @if (! $officeHoursService->isOpen()) disabled @endif
                            >
                                {{ __('common.actions.book') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
