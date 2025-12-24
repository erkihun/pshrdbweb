<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8">
                <form class="flex flex-wrap items-end gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex-1 min-w-[220px]">
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-400" for="q">{{ __('common.actions.search') }}</label>
                        <input
                            id="q"
                            name="q"
                            type="text"
                            value="{{ request('q') }}"
                            placeholder="Search by title or excerpt"
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

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($posts as $post)
                        <a
                            href="{{ $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug) }}"
                            class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-gray-300"
                        >
                            @if ($post->cover_image_path)
                                <img
                                    src="{{ asset('storage/' . $post->cover_image_path) }}"
                                    alt="{{ $post->display_title }}"
                                    class="mb-4 h-40 w-full rounded-xl object-cover"
                                >
                            @endif
                            <h3 class="text-lg font-semibold text-gray-900">{{ $post->display_title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                {{ $post->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->display_body), 140) }}
                            </p>
                            <div class="mt-auto pt-4 text-xs font-semibold uppercase tracking-wide text-gray-400">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : __('common.labels.recently_updated') }}
                            </div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500 md:col-span-2 lg:col-span-3">
                            {{ __('common.messages.no_posts') }}
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
