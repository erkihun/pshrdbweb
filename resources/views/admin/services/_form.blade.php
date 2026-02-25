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
            <label class="text-sm font-semibold text-slate-700" for="title_am">{{ __('common.labels.title') }} ({{ __('common.tabs.am') }})</label>
            <input
                id="title_am"
                name="title_am"
                type="text"
                value="{{ old('title_am', $service->title_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('title_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="description_am">{{ __('common.labels.description') }} ({{ __('common.tabs.am') }})</label>
            <textarea
                id="description_am"
                name="description_am"
                rows="6"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 js-editor"
                required
            >{{ old('description_am', $service->description_am ?? '') }}</textarea>
            @error('description_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="requirements_am">{{ __('common.labels.requirements') }} ({{ __('common.tabs.am') }})</label>
            <textarea
                id="requirements_am"
                name="requirements_am"
                rows="4"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 js-editor"
            >{{ old('requirements_am', $service->requirements_am ?? '') }}</textarea>
            @error('requirements_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div x-show="lang === 'en'" class="space-y-6">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="title_en">{{ __('common.labels.title') }} ({{ __('common.tabs.en') }})</label>
            <input
                id="title_en"
                name="title_en"
                type="text"
                value="{{ old('title_en', $service->title_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('title_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="description_en">{{ __('common.labels.description') }} ({{ __('common.tabs.en') }})</label>
            <textarea
                id="description_en"
                name="description_en"
                rows="6"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 js-editor"
                required
            >{{ old('description_en', $service->description_en ?? '') }}</textarea>
            @error('description_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-700" for="requirements_en">{{ __('common.labels.requirements') }} ({{ __('common.tabs.en') }})</label>
            <textarea
                id="requirements_en"
                name="requirements_en"
                rows="4"
                data-editor="tinymce"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 js-editor"
            >{{ old('requirements_en', $service->requirements_en ?? '') }}</textarea>
            @error('requirements_en')
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
                value="{{ old('sort_order', $service->sort_order ?? 0) }}"
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
                @checked(old('is_active', $service->is_active ?? true))
            >
            <label class="text-sm font-semibold text-slate-700" for="is_active">{{ __('common.status.active') }}</label>
        </div>
    </div>
</div>

@include('admin.components.editor-scripts')
