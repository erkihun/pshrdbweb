@php
    $metaTitle = $post->seo_title ?: $post->display_title;
    $metaDescription = $post->seo_description
        ?: ($post->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->display_body), 160));
    $ogImage = $post->cover_image_path ? asset('storage/' . $post->cover_image_path) : null;
@endphp

@push('title')
    {{ $metaTitle }}
@endpush

@push('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    {{ $type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}
                </div>
                <h1 class="mt-3 text-3xl font-semibold text-gray-900">{{ $post->display_title }}</h1>
                <p class="mt-2 text-sm text-gray-500">
                    {{ $post->published_at ? $post->published_at->format('M d, Y') : __('common.labels.recently_updated') }}
                </p>

                @if ($post->cover_image_path)
                    <img
                        src="{{ asset('storage/' . $post->cover_image_path) }}"
                        alt="{{ $post->display_title }}"
                        class="mt-6 h-64 w-full rounded-2xl object-cover"
                    >
                @endif

                @if ($post->display_excerpt)
                    <p class="mt-6 text-lg text-gray-700">{{ $post->display_excerpt }}</p>
                @endif

                <div class="prose prose-gray mt-6 max-w-none">
                    {!! nl2br(e($post->display_body)) !!}
                </div>

                <div class="mt-8 print-hidden">
                    <a
                        href="{{ $type === 'news' ? route('news.index') : route('announcements.index') }}"
                        class="btn-secondary"
                    >
                        {{ __('common.actions.back') }}
                    </a>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
