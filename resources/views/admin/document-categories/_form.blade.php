<div class="space-y-6">
    <div>
        <label class="text-sm font-semibold text-slate-700" for="name">{{ __('common.labels.title') }} ({{ __('common.tabs.en') }})</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $category->name ?? '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            required
        >
        @error('name')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="text-sm font-semibold text-slate-700" for="name_am">{{ __('common.labels.title') }} ({{ __('common.tabs.am') }})</label>
        <input
            id="name_am"
            name="name_am"
            type="text"
            value="{{ old('name_am', $category->name_am ?? '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            required
        >
        @error('name_am')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="sort_order">{{ __('common.labels.sort_order') }}</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $category->sort_order ?? 0) }}"
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
                @checked(old('is_active', $category->is_active ?? true))
            >
            <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
        </div>
    </div>
</div>
