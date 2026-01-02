@php
    $defaultDocumentLang = app()->getLocale() === 'en' ? 'en' : 'am';
    $defaultDocumentLang = in_array($defaultDocumentLang, ['am', 'en'], true) ? $defaultDocumentLang : 'am';
@endphp

<div class="space-y-6" x-data="{ lang: '{{ $defaultDocumentLang }}' }">
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
                value="{{ old('title_am', $document->title_am ?? '') }}"
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
                rows="5"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('description_am', $document->description_am ?? '') }}</textarea>
            @error('description_am')
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
                value="{{ old('title_en', $document->title_en ?? '') }}"
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
                rows="5"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('description_en', $document->description_en ?? '') }}</textarea>
            @error('description_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="text-sm font-semibold text-slate-700" for="document_category_id">{{ __('common.labels.category') }}</label>
        <select
            id="document_category_id"
            name="document_category_id"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
        >
            <option value="">{{ __('common.labels.category') }}</option>
            @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('document_category_id', $document->document_category_id ?? '') === $category->id)>
                        {{ $category->display_name }}
                    </option>
            @endforeach
        </select>
        @error('document_category_id')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="text-sm font-semibold text-slate-700" for="file">{{ __('common.labels.file') }}</label>
        <input
            id="file"
            name="file"
            type="file"
            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            @if (!isset($document)) required @endif
        >
        @if (!empty($document?->file_path))
            <p class="mt-2 text-xs text-slate-500">Current: {{ $document->file_path }}</p>
        @endif
        @error('file')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-700" for="published_at">{{ __('common.labels.publish_date') }}</label>
            <input
                id="published_at"
                name="published_at"
                type="datetime-local"
                value="{{ old('published_at', isset($document) && $document->published_at ? $document->published_at->format('Y-m-d\\TH:i') : '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
            @error('published_at')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 pt-6">
            <input
                id="is_published"
                name="is_published"
                type="checkbox"
                value="1"
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                @checked(old('is_published', $document->is_published ?? false))
            >
            <label class="text-sm font-semibold text-slate-700" for="is_published">{{ __('common.labels.published') }}</label>
        </div>
    </div>
</div>
