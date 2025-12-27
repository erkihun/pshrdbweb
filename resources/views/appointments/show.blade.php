<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $appointmentService->display_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto space-y-10 px-4 sm:px-6 lg:px-8">
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

            <section class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4">
                        <div class="text-xs font-semibold uppercase tracking-widest text-blue-500">
                            {{ __('common.labels.appointment_service') }}
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900">
                            {{ $appointmentService->display_name }}
                        </h1>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            {{ $appointmentService->display_description }}
                        </p>
                    </div>
                    <div class="space-y-4 rounded-2xl bg-slate-50/60 p-5 text-sm text-slate-600">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-900 text-sm">
                                {{ __('common.labels.appointment_service_duration') }}
                            </span>
                            <span class="text-sm text-slate-600">
                                {{ $appointmentService->duration_minutes }} mins
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-900">
                                {{ __('common.gov.office_hours') }}
                            </span>
                            <span class="text-sm text-slate-600">
                                {{ $officeHoursService->summary() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            @php $officeOpen = $officeHoursService->isOpen(); @endphp
                            <span class="text-sm font-semibold text-slate-900">
                                {{ $officeOpen ? __('common.status.open') : __('common.status.closed') }}
                            </span>
                            <span class="text-sm text-slate-600">
                                {{ $officeOpen ? __('common.messages.chat_hour_summary', ['hours' => $officeHoursService->summary()]) : __('common.messages.chat_closed_on_hours', ['hours' => $officeHoursService->summary()]) }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            @if($slots->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-5 text-sm text-slate-500">
                    No appointment slots are currently available. Please check back later or try another service.
                </div>
            @else
                <form method="POST" action="{{ route('appointments.book', $appointmentService) }}" class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    @csrf

                    <div class="space-y-6 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">
                                    {{ __('common.labels.appointment_slots') }}
                                </h3>
                                <p class="text-sm text-slate-500">
                                    Upcoming availability for this service.
                                </p>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                                {{ $slots->count() }} {{ __('common.labels.appointment_slots') }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            @foreach($slots as $slot)
                                <label
                                    class="relative flex cursor-pointer items-center gap-4 rounded-2xl border border-gray-200 bg-white p-4 transition focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 hover:border-blue-300"
                                >
                                    <input
                                        type="radio"
                                        name="appointment_slot_id"
                                        value="{{ $slot->id }}"
                                        class="peer sr-only"
                                        @checked(old('appointment_slot_id') === $slot->id)
                                        @disabled($slot->availableSeats === 0)
                                    >
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-4">
                                            <p class="text-sm font-semibold text-slate-900">
                                                {{ ethiopian_date($slot->starts_at, 'dd MMMM yyyy') }}
                                            </p>
                                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                {{ $slot->availableSeats > 0 ? __('common.messages.available') : 'Full' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-500">
                                            {{ ethiopian_date($slot->starts_at, 'h:mm a') }} - {{ ethiopian_date($slot->ends_at, 'h:mm a') }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $slot->availableSeats }} seats remaining · {{ $slot->capacity }} total
                                        </p>
                                    </div>
                                    <div class="text-right text-xs text-slate-500">
                                        <span class="block text-slate-900 font-semibold">
                                            {{ __('common.labels.appointment_time') }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            {{ $slot->starts_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="pointer-events-none absolute inset-0 rounded-2xl border-2 border-transparent transition peer-checked:border-blue-500"></div>
                                </label>
                            @endforeach

                            @error('appointment_slot_id')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ __('common.actions.book') }} {{ __('common.labels.appointment') }}
                        </h3>
                        <p class="text-sm text-slate-500">
                            Provide your contact details and we will send a confirmation with your reference code.
                        </p>

                        <div class="mt-6 space-y-3">
                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-slate-700">
                                    {{ __('common.labels.full_name') }}
                                </label>
                                <input
                                    id="full_name"
                                    name="full_name"
                                    type="text"
                                    value="{{ old('full_name') }}"
                                    class="form-input"
                                    required
                                >
                                @error('full_name')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-slate-700">
                                    {{ __('common.labels.phone') }}
                                </label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    value="{{ old('phone') }}"
                                    class="form-input"
                                    required
                                >
                                @error('phone')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-slate-700">
                                    {{ __('common.labels.email') }}
                                </label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    class="form-input"
                                >
                                @error('email')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-slate-700">
                                    {{ __('common.labels.message') }}
                                </label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    class="form-textarea"
                                >{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">
                                    {{ __('common.actions.submit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
