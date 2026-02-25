<div class="space-y-8" x-data="{ lang: 'am' }">
    <!-- Post Type & Status -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('common.labels.post_settings') }}</h3>
            <p class="mt-1 text-sm text-slate-500">Configure post type and publication status</p>
        </div>
        <div class="p-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="type">{{ __('common.labels.type') }}</label>
                    @php
                        $typeValue = old('type', $post->type ?? $fixedType ?? 'news');
                    @endphp
                    @if(empty($fixedType))
                        <select
                            id="type"
                            name="type"
                            class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            required
                        >
                            <option value="news" @selected($typeValue === 'news')>{{ __('common.nav.news') }}</option>
                            <option value="announcement" @selected($typeValue === 'announcement')>{{ __('common.nav.announcements') }}</option>
                        </select>
                    @else
                        <input type="hidden" name="type" value="{{ $fixedType }}">
                        <p class="mt-2 text-sm text-slate-500">{{ __('common.nav.' . $fixedType) }}</p>
                    @endif
                    @error('type')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="flex items-start gap-3 pt-6">
                    <div class="flex h-5 items-center">
                        <input
                            id="is_published"
                            name="is_published"
                            type="checkbox"
                            value="1"
                            class="h-4 w-4 rounded border-slate-300 bg-white text-indigo-600 shadow-sm transition focus:ring-indigo-500 focus:ring-offset-0"
                            @checked(old('is_published', $post->is_published ?? false))
                        >
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-900" for="is_published">{{ __('common.labels.published') }}</label>
                        <p class="mt-1 text-xs text-slate-500">Make this post visible to visitors</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Language Tabs -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('common.labels.content') }}</h3>
            <p class="mt-1 text-sm text-slate-500">Add content in multiple languages</p>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <div class="inline-flex rounded-lg border border-slate-200 p-1" role="tablist">
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all"
                        :class="lang === 'am' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
                        @click="lang = 'am'"
                    >
                        <svg class="h-4 w-4" :class="lang === 'am' ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                        </svg>
                        {{ __('common.tabs.am') }}
                    </button>
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-all"
                        :class="lang === 'en' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
                        @click="lang = 'en'"
                    >
                        <svg class="h-4 w-4" :class="lang === 'en' ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('common.tabs.en') }}
                    </button>
                </div>
            </div>

            <!-- Armenian Content -->
            <div x-show="lang === 'am'" class="space-y-6" x-transition>
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="title_am">
                        {{ __('common.labels.title') }}
                        <span class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('common.tabs.am') }}</span>
                    </label>
                    <input
                        id="title_am"
                        name="title_am"
                        type="text"
                        value="{{ old('title_am', $post->title_am ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        required
                    >
                    @error('title_am')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900" for="excerpt_am">
                        {{ __('common.labels.excerpt') }}
                        <span class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('common.tabs.am') }}</span>
                    </label>
                    <textarea
                        id="excerpt_am"
                        name="excerpt_am"
                        rows="3"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 js-editor"
                    >{{ old('excerpt_am', $post->excerpt_am ?? '') }}</textarea>
                    @error('excerpt_am')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <!-- English Content -->
            <div x-show="lang === 'en'" class="space-y-6" x-transition>
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="title_en">
                        {{ __('common.labels.title') }}
                        <span class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('common.tabs.en') }}</span>
                    </label>
                    <input
                        id="title_en"
                        name="title_en"
                        type="text"
                        value="{{ old('title_en', $post->title_en ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                    @error('title_en')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900" for="excerpt_en">
                        {{ __('common.labels.excerpt') }}
                        <span class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('common.tabs.en') }}</span>
                    </label>
                    <textarea
                        id="excerpt_en"
                        name="excerpt_en"
                        rows="3"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 js-editor"
                    >{{ old('excerpt_en', $post->excerpt_en ?? '') }}</textarea>
                    @error('excerpt_en')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Publish Date & Cover Image -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('common.labels.media') }}</h3>
            <p class="mt-1 text-sm text-slate-500">Schedule publication and add cover image</p>
        </div>
        <div class="p-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="published_at">
                        {{ __('common.labels.publish_date') }}
                        <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">Optional</span>
                    </label>
                    <input
                        id="published_at"
                        name="published_at"
                        type="datetime-local"
                        value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\\TH:i') : '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                    @error('published_at')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900" for="cover_image">
                        {{ __('common.labels.cover_image') }}
                        <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">Max 5MB</span>
                    </label>
                    <div class="mt-2">
                        <div class="flex items-center gap-4">
                            <label for="cover_image" class="cursor-pointer">
                                <div class="flex items-center gap-2 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 transition hover:border-indigo-400 hover:bg-indigo-50/50">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Choose File
                                </div>
                                <input
                                    id="cover_image"
                                    name="cover_image"
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                >
                            </label>
                            @if (!empty($post?->cover_image_path))
                                <div class="flex-1">
                                    <p class="text-sm text-slate-600">Current image:</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $post->cover_image_path }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @error('cover_image')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Body Content -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('common.labels.detailed_content') }}</h3>
            <p class="mt-1 text-sm text-slate-500">Main content in both languages</p>
        </div>
        <div class="p-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="body_am">
                        {{ __('common.labels.body') }}
                        <span class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('common.tabs.am') }}</span>
                    </label>
                    <textarea
                        id="body_am"
                        name="body_am"
                        rows="8"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 js-editor"
                        required
                    >{{ old('body_am', $post->body_am ?? '') }}</textarea>
                    @error('body_am')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900" for="body_en">
                        {{ __('common.labels.body') }}
                        <span class="ml-2 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('common.tabs.en') }}</span>
                    </label>
                    <textarea
                        id="body_en"
                        name="body_en"
                        rows="8"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 js-editor"
                    >{{ old('body_en', $post->body_en ?? '') }}</textarea>
                    @error('body_en')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Section -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ __('common.labels.seo') }}</h3>
            <p class="mt-1 text-sm text-slate-500">Search engine optimization settings</p>
        </div>
        <div class="p-6">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="seo_title">
                        {{ __('common.labels.seo_title') }}
                        <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">Optional</span>
                    </label>
                    <input
                        id="seo_title"
                        name="seo_title"
                        type="text"
                        value="{{ old('seo_title', $post->seo_title ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                    @error('seo_title')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900" for="seo_description">
                        {{ __('common.labels.seo_description') }}
                        <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">Optional</span>
                    </label>
                    <textarea
                        id="seo_description"
                        name="seo_description"
                        rows="3"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 js-editor"
                    >{{ old('seo_description', $post->seo_description ?? '') }}</textarea>
                    @error('seo_description')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    @if(!empty($post?->body))
        <!-- Single Body Field (Kept for backward compatibility) -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-900">Legacy Body Field</h3>
                <p class="mt-1 text-sm text-slate-500">For backward compatibility</p>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-sm font-medium text-slate-900" for="body">
                        Body (Legacy)
                        <span class="ml-2 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Deprecated</span>
                    </label>
                        <textarea
                            id="body"
                            name="body"
                            rows="8"
                            data-editor="tinymce"
                            class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 js-editor"
                        >{{ old('body', $post->body ?? '') }}</textarea>
                    @error('body')
                        <div class="mt-2 flex items-center gap-2 text-xs text-rose-600">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@include('admin.components.editor-scripts')
