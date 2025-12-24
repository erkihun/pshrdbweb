<div class="space-y-6" x-data="{ tab: 'am' }">
    <div class="flex flex-wrap items-center gap-3">
        <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-semibold"
            :class="tab === 'am' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="tab = 'am'"
        >
            {{ __('common.tabs.am') }}
        </button>
        <button
            type="button"
            class="rounded-lg px-4 py-2 text-sm font-semibold"
            :class="tab === 'en' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="tab = 'en'"
        >
            {{ __('common.tabs.en') }}
        </button>
    </div>

    <div x-show="tab === 'am'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="name_am">
                {{ __('common.labels.title') }} ({{ __('common.tabs.am') }})
            </label>
            <input
                id="name_am"
                name="name_am"
                type="text"
                value="{{ old('name_am', $service->name_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('name_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="description_am">
                {{ __('common.labels.description') }} ({{ __('common.tabs.am') }})
            </label>
            <textarea
                id="description_am"
                name="description_am"
                rows="4"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('description_am', $service->description_am ?? '') }}</textarea>
            @error('description_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div x-show="tab === 'en'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="name_en">
                {{ __('common.labels.title') }} ({{ __('common.tabs.en') }})
            </label>
            <input
                id="name_en"
                name="name_en"
                type="text"
                value="{{ old('name_en', $service->name_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('name_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="description_en">
                {{ __('common.labels.description') }} ({{ __('common.tabs.en') }})
            </label>
            <textarea
                id="description_en"
                name="description_en"
                rows="4"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('description_en', $service->description_en ?? '') }}</textarea>
            @error('description_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="duration_minutes">
                {{ __('common.labels.appointment_service_duration') }}
            </label>
            <input
                id="duration_minutes"
                name="duration_minutes"
                type="number"
                min="15"
                step="5"
                value="{{ old('duration_minutes', $service->duration_minutes ?? 30) }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('duration_minutes')
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
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
            @error('sort_order')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <input
            id="is_active"
            name="is_active"
            type="checkbox"
            value="1"
            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
            @checked(old('is_active', $service->is_active ?? true))
        >
        <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
    </div>
</div>
