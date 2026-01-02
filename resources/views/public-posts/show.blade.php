@php
    $metaTitle = $post->seo_title ?: $post->display_title;
    $metaDescription = $post->seo_description
        ?: ($post->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->display_body), 160));
    $ogImage = $post->cover_image_path ? asset('storage/' . $post->cover_image_path) : null;
    $siteSettings = app(\App\Services\SiteSettingsService::class)->all();
    $contact = $siteSettings['site.contact'] ?? [];
    $location = $contact['address_en'] ?? 'Addis Ababa, Ethiopia';
    $currentDate = now()->format('F d, Y');
    $lastUpdated = $post->updated_at?->timezone('Africa/Addis_Ababa')->format('F d, Y H:i')
        ?? $post->published_at?->timezone('Africa/Addis_Ababa')->format('F d, Y H:i')
        ?? $currentDate;
    $ethiopianFormatter = new \IntlDateFormatter(
        'am_ET@calendar=ethiopic',
        \IntlDateFormatter::FULL,
        \IntlDateFormatter::SHORT,
        'Africa/Addis_Ababa',
        \IntlDateFormatter::TRADITIONAL,
        "EEEE, dd MMMM yyyy h:mm a"
    );
    $ethiopianDateTime = $ethiopianFormatter->format(now()->timezone('Africa/Addis_Ababa'));
    $weatherInfo = $weatherInfo ?? ($siteSettings['site.meta.weather'] ?? 'Addis Ababa weather unavailable');
@endphp

@php
    $seoMeta = [
        'title' => $metaTitle,
        'description' => $metaDescription,
        'image' => $ogImage,
        'url' => $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug),
        'canonical' => $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug),
        'type' => 'article',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->display_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $badges = [
                        [
                            'label' => 'Location',
                            'value' => $location,
                            'icon' => '<svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-6.5 8-11a8 8 0 10-16 0c0 4.5 8 11 8 11z"/></svg>',
                        ],
                        [
                            'label' => 'Date',
                            'value' => $ethiopianDateTime,
                            'icon' => '<svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 8h14a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V9a1 1 0 011-1z"/></svg>',
                        ],
                        [
                            'label' => 'Last updated',
                            'value' => $lastUpdated,
                            'icon' => '<svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-9-9"/></svg>',
                        ],
                        [
                            'label' => 'Weather',
                            'value' => $weatherInfo,
                            'icon' => '<svg class="h-5 w-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 118 0h2a5 5 0 110 10H3v-10z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10l4-4m0 0l4 4m-4-4v12"/></svg>',
                        ],
                    ];
                @endphp

                @foreach($badges as $badge)
                    <div class="">
                        <div class="flex items-center gap-3 text-sm font-semibold text-gray-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100/50">
                                <span class="sr-only">{{ $badge['label'] }}</span>
                                {!! $badge['icon'] !!}
                            </div>
                            <span>{{ $badge['value'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
                <aside class="order-2 lg:order-1" style="min-height: calc(120vh - 6rem);">
                    <div class="sticky top-24 flex h-[100vh] flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                        <div class="px-6 py-8 border-b border-gray-100 bg-gradient-to-br from-blue-50 to-white">
                            <div class="text-base font-semibold uppercase  text-blue-600">
                                {{ __('common.nav.news') }}
                            </div>
                            <p class="mt-2 text-lg font-semibold text-blue-900">Latest updates News</p>
                            <p class="mt-1 text-xs uppercase  text-blue-400">Stay informed instantly</p>
                        </div>
                        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-4" style="max-height: calc(4 * 18rem + 4rem);">
                            @forelse($relatedPosts as $item)
                                @php
                                    $isCurrent = $post->id === $item->id;
                                    $thumb = $item->cover_image_path ? asset('storage/' . $item->cover_image_path) : asset('images/news-placeholder.jpg');
                                @endphp
                                <a
                                    href="{{ $type === 'news' ? route('news.show', $item->slug) : route('announcements.show', $item->slug) }}"
                                    class="flex w-full flex-col gap-3 rounded-2xl px-4 py-3 transition duration-200 min-h-[14rem]
                                        {{ $isCurrent ? 'bg-blue-50 text-blue-700 shadow-inner' : 'bg-gray-50 hover:bg-white hover:text-blue-800 shadow-sm' }}"
                                >
                                    <div class="h-28 w-full overflow-hidden rounded-xl bg-gray-100">
                                        <img
                                            src="{{ $thumb }}"
                                            alt="{{ $item->display_title }} thumbnail"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                        >
                                    </div>
                                    <div class="flex flex-col gap-1 text-sm">
                                        <h4 class="text-base font-semibold leading-snug text-ellipsis overflow-hidden">
                                            {{ $item->display_title }}
                                        </h4>
                                        <div class="flex items-center gap-3 text-[10px] uppercase tracking-[0.3em] text-gray-400">
                                            <span>{{ $item->published_at ? ethiopian_date($item->published_at, 'dd MMMM yyyy') : __('common.labels.recently_updated') }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-3">
                                            {{ $item->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->display_body), 80) }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-gray-500">No other items available.</p>
                            @endforelse
                        </div>
                    </div>
                </aside>

                <article class="order-1 lg:order-2 rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        {{ $type === 'news' ? __('common.nav.news') : __('common.nav.announcements') }}
                    </div>
                    <h1 class="mt-3 text-3xl font-semibold text-gray-900">{{ $post->display_title }}</h1>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $post->published_at ? ethiopian_date($post->published_at, 'dd MMMM yyyy') : __('common.labels.recently_updated') }}
                    </p>

                    @if ($post->cover_image_path)
                        <img
                            src="{{ asset('storage/' . $post->cover_image_path) }}"
                            alt="{{ $post->display_title }}"
                            class="mt-6 h-64 w-full rounded-2xl object-cover"
                            loading="lazy"
                        >
                    @endif

                    @if ($post->display_excerpt)
                        <p class="mt-6 text-lg text-gray-700">{{ $post->display_excerpt }}</p>
                    @endif

                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <x-rich-content class="prose prose-slate text-justify">
                            {!! $post->display_body !!}
                        </x-rich-content>
                    </div>

                    <div class="mt-8 print-hidden">
                        <a
                            href="{{ $type === 'news' ? route('news.index') : route('announcements.index') }}"
                            class="btn-primary"
                        >
                            {{ __('common.actions.back') }}
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</x-app-layout>
