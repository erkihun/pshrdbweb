<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.labels.search_results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8">
                <form method="GET" action="{{ route('search.index') }}" class="flex flex-wrap items-end gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex-1 min-w-[220px]">
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="q">{{ __('common.actions.search') }}</label>
                        <input
                            id="q"
                            name="q"
                            type="text"
                            value="{{ $query }}"
                            placeholder="{{ __('common.labels.search_placeholder') }}"
                            class="mt-2 w-full rounded-lg border border-gray-200 px-4 py-2 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        >
                    </div>
                    <div>
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800"
                        >
                            {{ __('common.actions.search') }}
                        </button>
                    </div>
                </form>

                @if (! $hasQuery)
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">
                        {{ __('common.messages.search_prompt') }}
                    </div>
                @else
                    @php
                        $hasResults = ($posts && $posts->total() > 0)
                            || ($services && $services->total() > 0)
                            || ($documents && $documents->total() > 0)
                            || ($pages && $pages->total() > 0);
                        $pageRoutes = [
                            'about' => 'pages.about',
                            'mission_vision_values' => 'pages.mission',
                            'leadership' => 'pages.leadership',
                            'structure' => 'pages.structure',
                            'history' => 'pages.history',
                        ];
                    @endphp

                    @if (! $hasResults)
                        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">
                            {{ __('common.messages.no_search_results') }}
                        </div>
                    @endif

                    <div class="space-y-10">
                        <section class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('common.labels.news_announcements') }}</h3>
                                @if ($posts)
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $posts->total() }}</span>
                                @endif
                            </div>

                            <div class="grid gap-4">
                                @if ($posts && $posts->count())
                                    @foreach ($posts as $post)
                                        <a
                                            href="{{ $post->type === 'announcement' ? route('announcements.show', $post->slug) : route('news.show', $post->slug) }}"
                                            class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300"
                                        >
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-base font-semibold text-gray-900">{{ $post->display_title }}</h4>
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                                    {{ $post->type === 'announcement' ? __('common.nav.announcements') : __('common.nav.news') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                {{ $post->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->display_body), 160) }}
                                            </p>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-500">
                                        {{ __('common.messages.no_posts') }}
                                    </div>
                                @endif
                            </div>

                            @if ($posts)
                                {{ $posts->links() }}
                            @endif
                        </section>

                        <section class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('common.nav.services') }}</h3>
                                @if ($services)
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $services->total() }}</span>
                                @endif
                            </div>

                            <div class="grid gap-4">
                                @if ($services && $services->count())
                                    @foreach ($services as $service)
                                        <a
                                            href="{{ route('services.show', $service->slug) }}"
                                            class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300"
                                        >
                                            <h4 class="text-base font-semibold text-gray-900">{{ $service->display_title }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($service->display_description ?: $service->display_requirements), 160) }}
                                            </p>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-500">
                                        {{ __('common.messages.no_services') }}
                                    </div>
                                @endif
                            </div>

                            @if ($services)
                                {{ $services->links() }}
                            @endif
                        </section>

                        <section class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('common.nav.downloads') }}</h3>
                                @if ($documents)
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $documents->total() }}</span>
                                @endif
                            </div>

                            <div class="grid gap-4">
                                @if ($documents && $documents->count())
                                    @foreach ($documents as $document)
                                        <a
                                            href="{{ route('downloads.show', $document->slug) }}"
                                            class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300"
                                        >
                                            <h4 class="text-base font-semibold text-gray-900">{{ $document->display_title }}</h4>
                                            @if ($document->display_description)
                                                <p class="text-sm text-gray-600">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($document->display_description), 160) }}
                                                </p>
                                            @endif
                                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ strtoupper($document->file_type) }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-500">
                                        {{ __('common.messages.no_documents') }}
                                    </div>
                                @endif
                            </div>

                            @if ($documents)
                                {{ $documents->links() }}
                            @endif
                        </section>

                        <section class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('common.labels.pages') }}</h3>
                                @if ($pages)
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $pages->total() }}</span>
                                @endif
                            </div>

                            <div class="grid gap-4">
                                @if ($pages && $pages->count())
                                    @foreach ($pages as $page)
                                        @php
                                            $routeName = $pageRoutes[$page->key] ?? null;
                                        @endphp
                                        @if ($routeName)
                                            <a
                                                href="{{ route($routeName) }}"
                                                class="flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-300"
                                            >
                                                <h4 class="text-base font-semibold text-gray-900">{{ $page->display_title }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($page->display_body), 160) }}
                                                </p>
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-500">
                                        {{ __('common.messages.no_pages') }}
                                    </div>
                                @endif
                            </div>

                            @if ($pages)
                                {{ $pages->links() }}
                            @endif
                        </section>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
