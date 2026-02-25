@php
    $listingTitle = $type === 'news' ? __('common.nav.news') : __('common.nav.announcements');
    $listingDescription = $type === 'news'
        ? 'Latest news updates from the Addis Ababa public service.'
        : 'Important announcements and official notices from the administration.';
    $listingUrl = $type === 'news' ? route('news.index') : route('announcements.index');
    $seoMeta = [
        'title' => $listingTitle,
        'description' => $listingDescription,
        'url' => $listingUrl,
        'canonical' => $listingUrl,
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                    {{ $type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}
                </h2>
                <p class="mt-2 text-gray-600">
                    {{ $type === 'news' ? 'Latest updates and official news' : 'Important announcements and notices' }}
                </p>
            </div>
            @if($posts->count() > 0)
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-200">
                    <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    {{ $posts->total() }} {{ strtolower($type === 'news' ? __('common.nav.news') : __('common.nav.announcements')) }}
                </span>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Search and Filter Section --}}
            <div class="mb-10">
                <form class="rounded-2xl border border-gray-100 bg-gradient-to-br from-white to-gray-50/50 p-6 shadow-sm ring-1 ring-gray-200/50">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:gap-6">
                        {{-- Search Input --}}
                        <div class="flex-1 min-w-0">
                            <label for="q" class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                {{ __('common.actions.search') }}
                            </label>
                            <div class="relative">
                                <input
                                    id="q"
                                    name="q"
                                    type="text"
                                    value="{{ request('q') }}"
                                    placeholder="Search by title, content, or keywords"
                                    class="block w-full rounded-xl border-0 bg-white/80 py-3 pl-12 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                />
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Date Filter --}}
                        @if($posts->count() > 0)
                        <div class="w-full lg:w-48">
                            <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Date</label>
                            <select
                                id="date"
                                name="date"
                                onchange="this.form.submit()"
                                class="block w-full rounded-xl border-0 bg-white/80 py-3 pl-4 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                            >
                                <option value="">All dates</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This week</option>
                                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This month</option>
                                <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>This year</option>
                            </select>
                        </div>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:from-blue-700 hover:to-blue-800 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                {{ __('common.actions.search') }}
                            </button>
                            @if(request()->hasAny(['q', 'date']))
                                <a
                                    href="{{ url()->current() }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                                >
                                    Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Posts Grid --}}
            @if($posts->count() > 0)
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:gap-8">
                    @foreach ($posts as $post)
                        <article class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-transparent hover:shadow-xl hover:shadow-blue-500/10">
                            {{-- Featured Badge --}}
                            @if($post->is_featured)
                                <div class="absolute right-4 top-4 z-10">
                                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-orange-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                            @endif

                            {{-- Cover Image --}}
                            @if ($post->cover_image_path)
                                <div class="relative h-48 w-full overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                    <img
                                        src="{{ asset('storage/' . $post->cover_image_path) }}"
                                        alt="{{ $post->display_title }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null;
                                        this.src='{{ asset('images/news-placeholder.jpg') }}';"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold leading-tight text-gray-900 group-hover:text-blue-700 transition-colors">
                                        <a href="{{ $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug) }}" class="focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ $post->display_title }}
                                        </a>
                                    </h3>
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-3">
                                        {{ $post->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->display_body), 120) }}
                                    </p>
                                </div>

                                {{-- Meta Info --}}
                                <div class="mt-6 pt-5 border-t border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-blue-50 to-blue-100">
                                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="text-sm">
                                                <time datetime="{{ $post->published_at?->toIso8601String() }}" class="font-semibold text-gray-900">
                                                    {{ $post->published_at ? ethiopian_date($post->published_at, 'dd MMMM yyyy') : __('common.labels.recently_updated') }}
                                                </time>
                                            </div>
                                        </div>
                                        <div class="text-blue-600 group-hover:translate-x-1 transition-transform">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                                        <div class="inline-flex items-center gap-1">
                                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                                            </svg>
                                            <span class="font-semibold text-gray-900">Views: {{ number_format($post->views_count ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($posts->hasPages())
                    <div class="mt-12">
                        <nav class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-4" aria-label="Pagination">
                            <div class="flex flex-1 justify-between sm:justify-end">
                                {{-- Previous Page Link --}}
                                @if ($posts->onFirstPage())
                                    <span class="relative inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 cursor-not-allowed">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $posts->previousPageUrl() }}" class="relative inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        Previous
                                    </a>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($posts->hasMorePages())
                                    <a href="{{ $posts->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        Next
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="relative ml-3 inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300 cursor-not-allowed">
                                        Next
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </nav>

                        {{-- Page Info --}}
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ $posts->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $posts->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $posts->total() }}</span>
                                results
                            </p>
                        </div>
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="rounded-2xl border border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-white p-12 text-center">
                    <div class="mx-auto max-w-md">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-gray-100 to-gray-200">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-6 text-lg font-semibold text-gray-900">No {{ $type === 'news' ? 'news' : 'announcements' }} found</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            @if(request()->hasAny(['q', 'date']))
                                No results match your search criteria. Try different keywords or clear filters.
                            @else
                                No {{ $type === 'news' ? 'news articles' : 'announcements' }} have been published yet.
                            @endif
                        </p>
                        @if(request()->hasAny(['q', 'date']))
                            <div class="mt-6">
                                <a
                                    href="{{ url()->current() }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-blue-700 hover:to-blue-800 hover:shadow"
                                >
                                    Clear search filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Categories/Related (Optional) --}}
            @if($posts->count() > 0 && $type === 'news')
                <div class="mt-16 border-t border-gray-200 pt-12">
                    <h3 class="text-lg font-semibold text-gray-900">Browse by Category</h3>
                    <div class="mt-4 flex flex-wrap gap-3">
                        @php
                            $categories = [
                                ['name' => 'All', 'count' => $posts->total()],
                                ['name' => 'Latest', 'count' => $posts->where('created_at', '>=', now()->subDays(7))->count()],
                                ['name' => 'Featured', 'count' => $posts->where('is_featured', true)->count()],
                                ['name' => 'Official', 'count' => $posts->where('is_official', true)->count()],
                            ];
                        @endphp
                        @foreach($categories as $category)
                            <a
                                href="#"
                                class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700"
                            >
                                <span>{{ $category['name'] }}</span>
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600">
                                    {{ $category['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-submit date filter
        document.getElementById('date')?.addEventListener('change', function() {
            this.form.submit();
        });

        // Add active state to search input when focused
        document.getElementById('q')?.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
        });

        document.getElementById('q')?.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
        });

        // Track clicks on posts for analytics
        document.querySelectorAll('article a').forEach(link => {
            link.addEventListener('click', function() {
                const title = this.closest('article').querySelector('h3').textContent;
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'post_click', {
                        'event_category': '{{ $type }}',
                        'event_label': title
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
