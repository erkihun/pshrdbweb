@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.actions.edit') }}</h1>
            @php
                $translationKey = 'pages.sections.' . $key;
                $pageLabel = \Illuminate\Support\Facades\Lang::has($translationKey)
                    ? __($translationKey)
                    : ucfirst(str_replace('_', ' ', $key));
            @endphp
            <p class="text-sm text-slate-500">{{ $pageLabel }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.pages.update', $key) }}"
            enctype="multipart/form-data"
            class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            x-data="{ lang: 'am' }"
        >
            @csrf
            @method('PUT')

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
                        value="{{ old('title_am', $page?->title_am ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        required
                    >
                    @error('title_am')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="body_am">{{ __('common.labels.body') }} ({{ __('common.tabs.am') }})</label>
                    <textarea
                        id="body_am"
                        name="body_am"
                        rows="10"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 js-editor"
                        required
                    >{{ old('body_am', $page?->body_am ?? '') }}</textarea>
                    @error('body_am')
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
                        value="{{ old('title_en', $page?->title_en ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        required
                    >
                    @error('title_en')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="body_en">{{ __('common.labels.body') }} ({{ __('common.tabs.en') }})</label>
                    <textarea
                        id="body_en"
                        name="body_en"
                        rows="10"
                        data-editor="tinymce"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300 js-editor"
                        required
                    >{{ old('body_en', $page?->body_en ?? '') }}</textarea>
                    @error('body_en')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="cover_image">{{ __('common.labels.cover_image') }}</label>
                    <input
                        id="cover_image"
                        name="cover_image"
                        type="file"
                        accept="image/*"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                    @if (!empty($page?->cover_image_path))
                        <p class="mt-2 text-xs text-slate-500">
                            {{ __('common.messages.current_cover_image', ['path' => $page->cover_image_path]) }}
                        </p>
                    @endif
                </div>
                <div class="flex items-center gap-3 pt-8">
                    <input
                        id="is_published"
                        name="is_published"
                        type="checkbox"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                        @checked(old('is_published', $page?->is_published ?? false))
                    >
                    <label class="text-sm font-semibold text-slate-700" for="is_published">{{ __('common.labels.published') }}</label>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.pages.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    {{ __('common.actions.back') }}
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.save') }}
                </button>
            </div>
        </form>

    </div>
    @include('admin.components.editor-scripts')
@endsection
