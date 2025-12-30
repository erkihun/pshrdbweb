<div class="space-y-6" x-data="{ lang: 'am' }">
    <div class="flex flex-wrap items-center gap-3">
        <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-semibold"
            :class="lang === 'am' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="lang = 'am'"
        >
            {{ __('common.tabs.am') }}
        </button>
        <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-semibold"
            :class="lang === 'en' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="lang = 'en'"
        >
            {{ __('common.tabs.en') }}
        </button>
    </div>

    <div x-show="lang === 'am'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="name_am">{{ __('common.labels.title') }} ({{ __('common.tabs.am') }})</label>
            <input
                id="name_am"
                name="name_am"
                type="text"
                value="{{ old('name_am', $department->name_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('name_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div x-show="lang === 'en'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="name_en">{{ __('common.labels.title') }} ({{ __('common.tabs.en') }})</label>
            <input
                id="name_en"
                name="name_en"
                type="text"
                value="{{ old('name_en', $department->name_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('name_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="sort_order">{{ __('common.labels.sort_order') }}</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $department->sort_order ?? 0) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
            @error('sort_order')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center gap-3 pt-6">
            <input
                id="is_active"
                name="is_active"
                type="checkbox"
                value="1"
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                @checked(old('is_active', $department->is_active ?? true))
            >
            <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <div class="text-sm font-semibold text-slate-700">{{ __('common.citizen_charter.admin.form.address_details') }}</div>
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label class="text-sm font-semibold text-slate-700" for="building_name">
                    {{ __('common.citizen_charter.admin.form.building') }}
                </label>
                <input
                    id="building_name"
                    name="building_name"
                    type="text"
                    value="{{ old('building_name', $department->building_name ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                @error('building_name')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700" for="floor_number">
                    {{ __('common.citizen_charter.admin.form.floor') }}
                </label>
                <input
                    id="floor_number"
                    name="floor_number"
                    type="text"
                    value="{{ old('floor_number', $department->floor_number ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                @error('floor_number')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label class="text-sm font-semibold text-slate-700" for="side">
                    {{ __('common.citizen_charter.admin.form.side') }}
                </label>
                <select
                    id="side"
                    name="side"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <option value="">{{ __('common.actions.choose') }}</option>
                    <option value="left" @selected(old('side', $department->side ?? '') === 'left')>{{ __('common.sides.left') }}</option>
                    <option value="right" @selected(old('side', $department->side ?? '') === 'right')>{{ __('common.sides.right') }}</option>
                    <option value="center" @selected(old('side', $department->side ?? '') === 'center')>{{ __('common.sides.center') }}</option>
                </select>
                @error('side')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700" for="office_room">
                    {{ __('common.citizen_charter.admin.form.office_room') }}
                </label>
                <input
                    id="office_room"
                    name="office_room"
                    type="text"
                    value="{{ old('office_room', $department->office_room ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                @error('office_room')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
            <label class="text-sm font-semibold text-slate-700" for="department_address_note_am">
                {{ __('common.citizen_charter.admin.form.address_note_am') }}
            </label>
            <input
                id="department_address_note_am"
                name="department_address_note_am"
                type="text"
                value="{{ old('department_address_note_am', $department->department_address_note_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
                @error('department_address_note_am')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
            <label class="text-sm font-semibold text-slate-700" for="department_address_note_en">
                {{ __('common.citizen_charter.admin.form.address_note_en') }}
            </label>
            <input
                id="department_address_note_en"
                name="department_address_note_en"
                type="text"
                value="{{ old('department_address_note_en', $department->department_address_note_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
                @error('department_address_note_en')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
