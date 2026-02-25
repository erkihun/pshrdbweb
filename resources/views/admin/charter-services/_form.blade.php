@php
    $dayOptions = [
        'Mon' => ['label' => __('common.citizen_charter.working_days.mon'), 'local' => 'ሰኞ'],
        'Tue' => ['label' => __('common.citizen_charter.working_days.tue'), 'local' => 'ማክሰኞ'],
        'Wed' => ['label' => __('common.citizen_charter.working_days.wed'), 'local' => 'ረቡዕ'],
        'Thu' => ['label' => __('common.citizen_charter.working_days.thu'), 'local' => 'ሐሙስ'],
        'Fri' => ['label' => __('common.citizen_charter.working_days.fri'), 'local' => 'አርብ'],
        'Sat' => ['label' => __('common.citizen_charter.working_days.sat'), 'local' => 'ቅዳሜ'],
        'Sun' => ['label' => __('common.citizen_charter.working_days.sun'), 'local' => 'እሑድ'],
    ];

    $selectedDays = old('working_days', $service->working_days ?? []);
@endphp

<div class="space-y-6">
    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="department_id">
                {{ __('common.citizen_charter.admin.form.department') }}
            </label>
            <select
                id="department_id"
                name="department_id"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
                <option value="">{{ __('common.actions.choose') }}</option>
                @foreach($departments as $department)
                    <option
                        value="{{ $department->id }}"
                        @selected(old('department_id', $service->department_id ?? '') === $department->id)
                    >
                        {{ $department->display_name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="sort_order">
                {{ __('common.labels.sort_order') }}
            </label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $service->sort_order ?? 0) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
            @error('sort_order')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700">
                {{ __('common.citizen_charter.admin.form.service_name') }} ({{ __('common.tabs.am') }})
            </label>
            <input
                name="name_am"
                type="text"
                value="{{ old('name_am', $service->name_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                required
            >
            @error('name_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">
                {{ __('common.citizen_charter.admin.form.service_name') }} ({{ __('common.tabs.en') }})
            </label>
            <input
                name="name_en"
                type="text"
                value="{{ old('name_en', $service->name_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >
            @error('name_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="prerequisites_am">
                {{ __('common.citizen_charter.admin.form.prerequisites') }} ({{ __('common.tabs.am') }})
            </label>
            <textarea
                id="prerequisites_am"
                name="prerequisites_am"
                rows="4"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >{{ old('prerequisites_am', $service->prerequisites_am ?? '') }}</textarea>
            @error('prerequisites_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="prerequisites_en">
                {{ __('common.citizen_charter.admin.form.prerequisites') }} ({{ __('common.tabs.en') }})
            </label>
            <textarea
                id="prerequisites_en"
                name="prerequisites_en"
                rows="4"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >{{ old('prerequisites_en', $service->prerequisites_en ?? '') }}</textarea>
            @error('prerequisites_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700">{{ __('common.citizen_charter.admin.form.service_place') }} ({{ __('common.tabs.am') }})</label>
            <input
                name="service_place_am"
                type="text"
                value="{{ old('service_place_am', $service->service_place_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >
            @error('service_place_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700">{{ __('common.citizen_charter.admin.form.service_place') }} ({{ __('common.tabs.en') }})</label>
            <input
                name="service_place_en"
                type="text"
                value="{{ old('service_place_en', $service->service_place_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >
            @error('service_place_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4">
        <div class="mb-2 text-sm font-semibold text-slate-700">
            {{ __('common.citizen_charter.admin.form.working_days') }}
        </div>
        <div class="grid gap-2 md:grid-cols-2">
            @foreach($dayOptions as $value => $labels)
                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm transition hover:border-blue-300">
                    <input
                        type="checkbox"
                        name="working_days[]"
                        value="{{ $value }}"
                        @checked(in_array($value, $selectedDays))
                        class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-blue-500"
                    >
                    <span>
                        {{ $labels['label'] }} / {{ $labels['local'] }}
                    </span>
                </label>
            @endforeach
        </div>
        @error('working_days.*')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="working_hours_start">
                {{ __('common.citizen_charter.admin.form.opening_hours') }}
            </label>
            <div class="flex gap-2">
                <input
                    name="working_hours_start"
                    type="time"
                    value="{{ old('working_hours_start', $service->working_hours_start ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                >
                <span class="self-center text-sm text-slate-500">-</span>
                <input
                    name="working_hours_end"
                    type="time"
                    value="{{ old('working_hours_end', $service->working_hours_end ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                >
            </div>
            @error('working_hours_start')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
            @error('working_hours_end')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="break_time_start">
                {{ __('common.citizen_charter.admin.form.break_hours') }}
            </label>
            <div class="flex gap-2">
                <input
                    name="break_time_start"
                    type="time"
                    value="{{ old('break_time_start', $service->break_time_start ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                >
                <span class="self-center text-sm text-slate-500">-</span>
                <input
                    name="break_time_end"
                    type="time"
                    value="{{ old('break_time_end', $service->break_time_end ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                >
            </div>
            @error('break_time_start')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
            @error('break_time_end')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="service_delivery_mode">
                {{ __('common.citizen_charter.admin.form.delivery_mode') }}
            </label>
            <select
                id="service_delivery_mode"
                name="service_delivery_mode"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >
                <option value="">{{ __('common.actions.choose') }}</option>
                @foreach(\App\Models\CharterService::deliveryModeOptions() as $value => $label)
                    <option value="{{ $value }}" @selected(old('service_delivery_mode', $service->service_delivery_mode ?? '') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('service_delivery_mode')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="fee_amount">
                {{ __('common.citizen_charter.admin.form.fee') }}
            </label>
            <div class="mt-2 flex gap-2">
                <input
                    name="fee_amount"
                    type="number"
                    step="0.01"
                    min="0"
                    value="{{ old('fee_amount', $service->fee_amount ?? '') }}"
                    class="w-1/2 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                >
                <input
                    name="fee_currency"
                    type="text"
                    value="{{ old('fee_currency', $service->fee_currency ?? 'ETB') }}"
                    class="w-1/2 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
                >
            </div>
            @error('fee_amount')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
            @error('fee_currency')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="other_info_am">
                {{ __('common.citizen_charter.admin.form.other_info') }} ({{ __('common.tabs.am') }})
            </label>
            <textarea
                id="other_info_am"
                name="other_info_am"
                rows="4"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >{{ old('other_info_am', $service->other_info_am ?? '') }}</textarea>
            @error('other_info_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-700" for="other_info_en">
                {{ __('common.citizen_charter.admin.form.other_info') }} ({{ __('common.tabs.en') }})
            </label>
            <textarea
                id="other_info_en"
                name="other_info_en"
                rows="4"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200"
            >{{ old('other_info_en', $service->other_info_en ?? '') }}</textarea>
            @error('other_info_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-4">
        <input
            id="is_active"
            name="is_active"
            type="checkbox"
            value="1"
            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-blue-500"
            @checked(old('is_active', $service->is_active ?? true))
        >
        <label for="is_active" class="text-sm font-semibold text-slate-700">
            {{ __('common.status.active') }}
        </label>
    </div>
</div>

@include('admin.components.editor-scripts')
