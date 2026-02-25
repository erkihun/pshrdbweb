@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $post->display_title }}</h1>
                <p class="text-sm text-slate-500">{{ $post->slug }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.posts.edit', $post) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300"
                >
                    Edit
                </a>
                <a
                    href="{{ route('admin.posts.index') }}"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
                >
                    {{ __('common.actions.back') }}
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <dl class="grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.type') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.author_name') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->author_name ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.status') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->is_published ? __('common.status.published') : __('common.status.draft') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.posted_date') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->posted_at ? ethiopian_date($post->posted_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.publish_date') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->published_at ? ethiopian_date($post->published_at, 'dd MMMM yyyy h:mm a', 'Africa/Addis_Ababa', null, 'M d, Y H:i', true) : 'Not scheduled' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.seo_title') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->seo_title ?: 'â€”' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.seo_description') }}</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $post->seo_description ?: 'â€”' }}
                    </dd>
                </div>
            </dl>

            @if ($post->display_excerpt)
                <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.excerpt') }}</h2>
                    <p class="mt-2 whitespace-pre-line">{{ $post->display_excerpt }}</p>
                </div>
            @endif

            @if ($post->cover_image_path)
                <div class="mt-6">
                    <p class="text-xs uppercase tracking-wide text-slate-400">{{ __('common.labels.cover_image') }}</p>
                    <img
                        src="{{ asset('storage/' . $post->cover_image_path) }}"
                        alt="{{ $post->display_title }}"
                        class="mt-2 max-h-64 w-full rounded-lg object-cover"
                    >
                </div>
            @endif

            @if ($post->type === 'news' && $post->images->count())
                <div class="mt-6">
                    <p class="text-xs uppercase tracking-wide text-slate-400">News Photos</p>
                    <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($post->images as $image)
                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $post->display_title }}"
                                class="h-40 w-full rounded-lg object-cover"
                                loading="lazy"
                            >
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-6 border-t border-slate-100 pt-6 text-sm text-slate-700">
                <h2 class="text-sm font-semibold text-slate-900">{{ __('common.labels.body') }}</h2>
                <div class="mt-4">
                    <x-rich-content>
                        {!! $post->display_body !!}
                    </x-rich-content>
                </div>
            </div>
        </div>
    </div>
@endsection
