<div class="space-y-6" x-data="{ lang: 'am' }">
    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class=" font-semibold text-slate-700" for="type">{{ __('common.labels.type') }}</label>
            <select
                id="type"
                name="type"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
                <option value="news" @selected(old('type', $post->type ?? 'news') === 'news')>{{ __('common.nav.news') }}</option>
                <option value="announcement" @selected(old('type', $post->type ?? '') === 'announcement')>{{ __('common.nav.announcements') }}</option>
            </select>
            @error('type')
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
                @checked(old('is_published', $post->is_published ?? false))
            >
            <label class=" font-semibold text-slate-700" for="is_published">{{ __('common.labels.published') }}</label>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button
            type="button"
            class="rounded-lg px-4 py-2  font-semibold"
            :class="lang === 'am' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="lang = 'am'"
        >
            {{ __('common.tabs.am') }}
        </button>
        <button
            type="button"
            class="rounded-lg px-4 py-2  font-semibold"
            :class="lang === 'en' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="lang = 'en'"
        >
            {{ __('common.tabs.en') }}
        </button>
    </div>

    <div x-show="lang === 'am'" class="space-y-6">
        <div>
            <label class=" font-semibold text-slate-700" for="title_am">{{ __('common.labels.title') }} ({{ __('common.tabs.am') }})</label>
            <input
                id="title_am"
                name="title_am"
                type="text"
                value="{{ old('title_am', $post->title_am ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('title_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class=" font-semibold text-slate-700" for="excerpt_am">{{ __('common.labels.excerpt') }} ({{ __('common.tabs.am') }})</label>
            <textarea
                id="excerpt_am"
                name="excerpt_am"
                rows="3"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('excerpt_am', $post->excerpt_am ?? '') }}</textarea>
            @error('excerpt_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div x-show="lang === 'en'" class="space-y-6">
        <div>
            <label class=" font-semibold text-slate-700" for="title_en">{{ __('common.labels.title') }} ({{ __('common.tabs.en') }})</label>
            <input
                id="title_en"
                name="title_en"
                type="text"
                value="{{ old('title_en', $post->title_en ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >
            @error('title_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class=" font-semibold text-slate-700" for="excerpt_en">{{ __('common.labels.excerpt') }} ({{ __('common.tabs.en') }})</label>
            <textarea
                id="excerpt_en"
                name="excerpt_en"
                rows="3"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('excerpt_en', $post->excerpt_en ?? '') }}</textarea>
            @error('excerpt_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class=" font-semibold text-slate-700" for="published_at">{{ __('common.labels.publish_date') }}</label>
        <input
            id="published_at"
            name="published_at"
            type="datetime-local"
            value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\\TH:i') : '') }}"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
        >
        @error('published_at')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class=" font-semibold text-slate-700" for="cover_image">{{ __('common.labels.cover_image') }}</label>
        <input
            id="cover_image"
            name="cover_image"
            type="file"
            accept="image/*"
            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
        >
        @if (!empty($post?->cover_image_path))
            <p class="mt-2 text-xs text-slate-500">Current: {{ $post->cover_image_path }}</p>
        @endif
        @error('cover_image')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class=" font-semibold text-slate-700" for="body_am">{{ __('common.labels.body') }} ({{ __('common.tabs.am') }})</label>
            <textarea
                id="body_am"
                name="body_am"
                rows="8"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >{{ old('body_am', $post->body_am ?? '') }}</textarea>
            @error('body_am')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class=" font-semibold text-slate-700" for="body_en">{{ __('common.labels.body') }} ({{ __('common.tabs.en') }})</label>
            <textarea
                id="body_en"
                name="body_en"
                rows="8"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                required
            >{{ old('body_en', $post->body_en ?? '') }}</textarea>
            @error('body_en')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class=" font-semibold text-slate-700" for="seo_title">{{ __('common.labels.seo_title') }}</label>
            <input
                id="seo_title"
                name="seo_title"
                type="text"
                value="{{ old('seo_title', $post->seo_title ?? '') }}"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >
            @error('seo_title')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class=" font-semibold text-slate-700" for="seo_description">{{ __('common.labels.seo_description') }}</label>
            <textarea
                id="seo_description"
                name="seo_description"
                rows="3"
                class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            >{{ old('seo_description', $post->seo_description ?? '') }}</textarea>
            @error('seo_description')
                <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class=" font-semibold text-slate-700" for="body">Body</label>
        <textarea
            id="body"
            name="body"
            rows="8"
            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2  focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
            required
        >{{ old('body', $post->body ?? '') }}</textarea>
        @error('body')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
