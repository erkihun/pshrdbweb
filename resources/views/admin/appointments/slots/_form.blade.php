<div class="space-y-6">
    <div>
        <label class="text-sm font-semibold text-slate-700" for="appointment_service_id">
            {{ __('common.labels.appointment_service') }}
        </label>
        <select
            id="appointment_service_id"
            name="appointment_service_id"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            required
        >
            <option value="">{{ __('common.actions.choose') }}</option>
            @foreach ($services as $service)
                <option
                    value="{{ $service->id }}"
                    @selected(old('appointment_service_id', $appointmentSlot->appointment_service_id ?? '') === $service->id)
                >
                    {{ $service->display_name }}
                </option>
            @endforeach
        </select>
        @error('appointment_service_id')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="starts_at">
                {{ __('common.labels.appointment_date') }}
            </label>
            <input
                id="starts_at"
                name="starts_at"
                type="datetime-local"
                value="{{ old('starts_at', isset($appointmentSlot) ? $appointmentSlot->starts_at->format('Y-m-d\TH:i') : '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('starts_at')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="ends_at">
                {{ __('common.labels.appointment_time') }}
            </label>
            <input
                id="ends_at"
                name="ends_at"
                type="datetime-local"
                value="{{ old('ends_at', isset($appointmentSlot) ? $appointmentSlot->ends_at->format('Y-m-d\TH:i') : '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('ends_at')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="capacity">{{ __('common.labels.appointment_slots') }}</label>
            <input
                id="capacity"
                name="capacity"
                type="number"
                min="1"
                value="{{ old('capacity', $appointmentSlot->capacity ?? 1) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('capacity')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="sort_order">{{ __('common.labels.sort_order') }}</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $appointmentSlot->sort_order ?? 0) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
            @error('sort_order')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-3">
        <input
            id="is_active"
            name="is_active"
            type="checkbox"
            value="1"
            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
            @checked(old('is_active', $appointmentSlot->is_active ?? true))
        >
        <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
    </div>
</div>
