@extends('layouts.public')

@section('title', config('app.name'))

@section('content')
@php
    $heroSettings = $site_settings['site.branding'] ?? [];
    $heroSignatureLogo = $heroSettings['logo_path']
        ?? $heroSettings['logo_light']
        ?? $heroSettings['logo']
        ?? ($heroSettings['logo_' . app()->getLocale()] ?? null);
    $heroSignatureName = $heroSettings['site_name_' . app()->getLocale()] ?? config('app.name');
    $heroSignatureTag = __('common.hero.signature', ['site' => $heroSignatureName]);
    $homeDescription = $site_settings['site.seo']['description_' . app()->getLocale()] ?? 'Official Addis Ababa public service portal for news, announcements, and services.';
    $seoMeta = [
        'title' => $heroSignatureName,
        'description' => $homeDescription,
        'url' => url('/'),
    ];
@endphp
<div class="font-noto-ethiopic">
@php
    use App\Models\Department;
    use App\Models\HomeSlide;
    use App\Models\Post;
    use App\Models\Service;
    use App\Models\Staff;

    $slides = HomeSlide::query()
        ->active()
        ->ordered()
        ->get();

    $officialMessage = \App\Models\OfficialMessage::query()
        ->where('is_active', true)
        ->first();

    $officialName = $officialMessage ? $officialMessage->localized('name') ?? '' : '';
    $officialTitle = $officialMessage ? $officialMessage->localized('title') ?? '' : '';
    $officialMessageText = $officialMessage ? $officialMessage->localized('message') ?? '' : '';

    $officialInitials = $officialName
        ? collect(explode(' ', trim($officialName)))
            ->filter()
            ->take(2)
            ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
            ->join('')
        : '';

    $latestNews = Post::query()
        ->whereNotNull('published_at')
	   ->where('type', 'news')
        ->orderByDesc('published_at')
        ->take(6)
        ->get();

    $staffMembers = Staff::query()
        ->where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('sort_order')
        ->take(6)
        ->get();

    $services = Service::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->take(6)
        ->get();

    $charterDepartments = Department::query()
        ->where('is_active', true)
        ->withCount('charterServices')
        ->orderBy('sort_order')
        ->get();

    $viewActionClasses = 'inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:from-blue-700 hover:to-indigo-700 hover:shadow-blue-600/40';

    $type = 'news';
@endphp

{{-- ================= HERO SLIDER (TITLE/SUBTITLE BOTTOM) ================= --}}
<section id="hero" class="scroll-section is-visible relative w-full bg-gray-900 overflow-hidden">
    <div id="heroSlider" class="relative w-full min-h-[65vh] h-[75vh] overflow-hidden" >
        @forelse ($slides as $index => $slide)
            @php
                $transitionStyle = \Illuminate\Support\Str::of($slide->transition_style ?? 'wave')->kebab()->toString();
                $contentAlignment = \Illuminate\Support\Str::of($slide->content_alignment ?? 'center')->lower()->toString();
                $textAlignmentClass = match ($contentAlignment) {
                    'left' => 'text-left lg:text-left',
                    'right' => 'text-right lg:text-right',
                    default => 'text-center lg:text-center',
                };
            @endphp
            <div
                class="hero-slide motion-{{ $transitionStyle }} absolute inset-0 transition-all duration-900 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                data-transition-style="{{ $transitionStyle }}"
                data-content-alignment="{{ $contentAlignment }}"
                data-index="{{ $index }}"
            >
                {{-- Background image --}}
                <img
                    src="{{ asset('storage/' . $slide->image_path) }}"
                    alt="{{ $slide->title }}"
                    class="absolute inset-0 w-full h-full object-cover"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                />

                {{-- Dark overlay --}}
                <div class="absolute inset-0 bg-black/45"></div>

                {{-- Bottom gradient panel --}}
                <div class="absolute inset-x-0 bottom-0 h-44 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                {{-- Content (BOTTOM) --}}
                <div class="relative h-full flex items-end">
                    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12 pb-10">
                        <div class="max-w-3xl {{ $textAlignmentClass }}">
                            <h1 class="text-2xl md:text-3xl font-bold text-orange-500 leading-tight">
                                {{ $slide->title }}
                            </h1>

                            @if($slide->subtitle)
                                <p class="mt-3 text-base md:text-lg text-gray-200 leading-relaxed max-w-2xl">
                                    {{ $slide->subtitle }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Fallback --}}
            <div class="absolute inset-0 flex items-center justify-center bg-gray-800">
                <div class="text-center text-white px-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-500/20 mb-6">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-white">
                        {{ config('app.name') }}
                    </h1>
                    <p class="mt-4 text-lg text-gray-300">
                        {{ __('home.hero.fallback_description') }}
                    </p>
                </div>
            </div>
        @endforelse

        {{-- Navigation --}}
        @if($slides->count() > 1)
            <button
                type="button"
                onclick="sliderPrev()"
                class="group absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 text-white text-xl transition-all duration-300 transform hover:scale-110 active:scale-95 z-20"
                aria-label="{{ __('home.hero.prev_slide') }}"
            >
                ‹
            </button>

            <button
                type="button"
                onclick="sliderNext()"
                class="group absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 text-white text-xl transition-all duration-300 transform hover:scale-110 active:scale-95 z-20"
                aria-label="{{ __('home.hero.next_slide') }}"
            >
                ›
            </button>

            {{-- Slide indicators --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-20">
                    @foreach($slides as $index => $slide)
                        <button
                            type="button"
                            onclick="goToSlide({{ $index }})"
                            data-hero-indicator
                            class="hero-indicator w-3 h-3 rounded-full {{ $index === 0 ? 'indicator-active' : 'bg-white/50 hover:bg-white/80' }}"
                            aria-label="{{ __('home.hero.go_to_slide', ['number' => $index + 1]) }}"
                        ></button>
                    @endforeach
                </div>
        @endif

                @if($heroSignatureLogo)
                    <div class="pointer-events-none absolute right-6 bottom-6 z-20">
                        <img
                            src="{{ asset('storage/' . ltrim($heroSignatureLogo, '/')) }}"
                            alt="{{ $heroSignatureName }} logo"
                            class="h-10   object-cover shadow-2xl opacity-80 "
                            loading="lazy"
                        >
                    
                    </div>
                @endif
    </div>
</section>

{{-- ================= OFFICIAL MESSAGE ================= --}}
@if($officialMessage)
<section id="official-message" class="scroll-section bg-gradient-to-b from-white to-gray-50 border-t">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-stretch">
            {{-- PHOTO CARD --}}
            <div class="lg:col-span-1 lg:order-last">
                <div class="relative h-full rounded-3xl overflow-hidden group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur opacity-20 group-hover:opacity-30 transition duration-1000"></div>

                    <div class="relative h-full rounded-3xl overflow-hidden bg-gradient-to-br from-gray-100 to-white ring-1 ring-gray-200/50 backdrop-blur-sm">
                        @if($officialMessage->photo_path)
                            <img
                                src="{{ asset('storage/' . $officialMessage->photo_path) }}"
                                alt="{{ $officialName ?: __('home.official_message.portrait_label') }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                                loading="lazy"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                                <div class="text-center">
                                    <div class="w-32 h-32 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-6 shadow-lg">
                                        <span class="text-5xl font-bold text-white">
                                            {{ $officialInitials ?: '—' }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 font-medium">{{ __('home.official_message.portrait_label') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- MESSAGE CONTENT --}}
            <div class="lg:col-span-2">
                <div class="h-full rounded-3xl border border-gray-200/50 bg-gradient-to-br from-white to-gray-50/50 p-8 lg:p-10 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all duration-500">

                    @php
                        $officialMessageRole = '<span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">' . __('home.official_message.highlight') . '</span>';
                    @endphp
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">
                        {!! __('home.official_message.title', ['role' => $officialMessageRole]) !!}
                    </h2>

                    <div class="mt-8 flex-1 relative">
                        <div
                            id="officialMessageText"
                            class="text-gray-700 leading-relaxed whitespace-pre-line text-justify overflow-hidden transition-all duration-500 ease-out"
                            style="display:-webkit-box;-webkit-line-clamp:7;-webkit-box-orient:vertical;"
                            data-expanded="false"
                        >
                            {{ $officialMessageText }}
                        </div>

                        <div id="messageFade" class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent pointer-events-none transition-opacity duration-500"></div>

                        <button
                            id="officialMessageToggle"
                            type="button"
                            class="mt-8 hidden group inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 font-semibold hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 transform hover:-translate-y-0.5 ring-1 ring-blue-100"
                            data-read-more="{{ __('home.official_message.read_more') }}"
                            data-read-less="{{ __('home.official_message.read_less') }}"
                            onclick="toggleOfficialMessage()"
                        >
                            <span class="official-message-label group-hover:translate-y-[-1px] transition-transform">{{ __('home.official_message.read_more') }}</span>
                            <svg class="w-4 h-4 group-hover:translate-y-[-1px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-bold text-gray-900 text-xl">{{ $officialName }}</div>
                                <div class="text-gray-600 mt-1">{{ $officialTitle }}</div>
                            </div>
                            <div class="text-sm text-gray-500">
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ================= LATEST NEWS ================= --}}
@if($latestNews->count())
<section id="news-section" class="scroll-section bg-gradient-to-b from-gray-50 to-white py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl -z-10"></div>

        <div class="flex items-center justify-between mb-4 gap-6">
            <div class="flex items-center gap-3">
                <x-heroicon-o-newspaper class="h-6 w-6 text-blue-600" aria-hidden="true" />
                <h5 class="text-2xl lg:text-3xl font-bold text-gray-900">
                    <span class=" text-blue-600 ">{{ __('home.news.title') }}</span>
                </h5>
            </div>

                <a href="{{ url('/news') }}"
                   class="{{ $viewActionClasses }} hidden lg:inline-flex">
                    <span>{{ __('home.news.view_all') }}</span>
                <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($latestNews as $post)
                <a
                    href="{{ $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug) }}"
                    class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 pb-5 shadow-sm transition duration-500 hover:-translate-y-1 hover:border-transparent hover:bg-white"
                >
                    <span class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 h-16 w-16 rounded-full bg-orange-500 opacity-20 transition duration-500 group-hover:scale-110 group-hover:opacity-70"></span>
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl opacity-0 group-hover:opacity-10 blur transition duration-500"></div>
                    @if ($post->cover_image_path)
                        <div class="relative mb-4 h-48 w-full rounded-xl overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $post->cover_image_path) }}"
                                alt="{{ $post->display_title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            <div class="absolute top-3 left-3 rounded-full bg-orange-500 px-3 py-1 text-xs font-semibold text-white shadow">
                                {{ __('home.news.badge') }}
                            </div>
                        </div>
                    @endif

                        <div class="relative z-10 flex-1 flex flex-col gap-3">
                        <h4 class="text-xl font-bold text-gray-900 text-justify group-hover:text-blue-700 transition-colors duration-300">
                            {{ $post->display_title }}
                        </h4>

                        

                        <div class="mt-auto rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 text-sm text-blue-800 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="font-semibold text-gray-500">
                                    {{ $post->published_at ? ethiopian_date($post->published_at, 'dd MMMM yyyy') : __('common.labels.recently_updated') }}
                                </span>
                            </div>
                            <div class="text-blue-600 transition-transform duration-300 group-hover:translate-x-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12 lg:hidden text-center">
                <a href="{{ url('/news') }}"
                   class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:-translate-y-1">
                {{ __('home.news.mobile_button') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<section id="quick-access-section" class="scroll-section bg-gradient-to-b from-white to-gray-50 py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="flex items-center gap-3 mb-8">
            <x-heroicon-o-link class="h-6 w-6 text-blue-600" aria-hidden="true" />
            <h5 class="text-2xl lg:text-3xl font-bold text-gray-900">Stay connected</h5>
        </div>
        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg shadow-blue-500/10">
            <div class="grid gap-8 lg:grid-cols-3 items-stretch">
                <div class="flex flex-col h-full group">
                    
                    <div class="relative mt-6 flex-1 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-500 ease-out group-hover:-translate-y-1 group-hover:shadow-[0_20px_45px_rgba(37,99,235,0.4)]">
                        <iframe
                            class="h-full w-full"
                            src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fweb.facebook.com%2Fprofile.php%3Fid%3D100067771711638&tabs=timeline&width=500&height=600&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
                            width="500"
                            height="600"
                            style="border:none;overflow:hidden"
                            scrolling="no"
                            frameborder="0"
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                            title="AAC Public Facebook page preview"
                        ></iframe>
                    </div>
                </div>
                <div class="flex flex-col h-full group">
                    <div class="mt-6 flex-1 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-500 ease-out group-hover:-translate-y-1 group-hover:shadow-[0_20px_45px_rgba(239,68,68,0.4)]">
                        <iframe
                            class="h-full w-full"
                            src="https://ethiocoders.et/"
                            loading="lazy"
                            style="border:none;overflow:hidden"
                            scrolling="yes"
                            frameborder="0"
                            title="Ethiocoders quick access"
                        ></iframe>
                    </div>
                </div>
                <div class="flex flex-col h-full group">
                    <div class="mt-6 flex-1 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-500 ease-out group-hover:-translate-y-1 group-hover:shadow-[0_20px_45px_rgba(16,185,129,0.45)]">
                        <iframe
                            class="h-full w-full"
                            src="https://addis.mesobcenter.et/"
                            loading="lazy"
                            style="border:none;overflow:hidden"
                            scrolling="yes"
                            frameborder="0"
                            title="Addis Mesob Center quick access"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@if($staffMembers->count())
<section id="leaders-section" class="scroll-section bg-transparent py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
      
        
        <div class="flex items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-3">
                <x-heroicon-o-user-group class="h-6 w-6 text-indigo-600" aria-hidden="true" />
                <div>
                    @php
                        $leadersHighlight = '<span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">' . __('home.leaders.highlight') . '</span>';
                    @endphp
                    <h5 class="text-2xl lg:text-3xl font-bold text-gray-900">
                        {!! __('home.leaders.title', ['highlight' => $leadersHighlight]) !!}
                    </h5>
                </div>
            </div>

            {{-- Slider controls (desktop) --}}
            <div class="hidden md:flex items-center gap-3">
                <button
                    type="button"
                    onclick="goToLeaderSlide('prev')"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition"
                    aria-label="{{ __('home.leaders.previous') }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    type="button"
                    onclick="goToLeaderSlide('next')"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition"
                    aria-label="{{ __('home.leaders.next') }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="relative">
            {{-- Mobile controls floating on sides --}}
            <button
                type="button"
                onclick="goToLeaderSlide('prev')"
                class="md:hidden absolute left-0 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/90 border border-gray-200 text-gray-700 shadow-sm"
                aria-label="{{ __('home.leaders.previous') }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button
                type="button"
                onclick="goToLeaderSlide('next')"
                class="md:hidden absolute right-0 top-1/2 -translate-y-1/2 z-10 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/90 border border-gray-200 text-gray-700 shadow-sm"
                aria-label="{{ __('home.leaders.next') }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Horizontal slider track --}}
            <div
                id="leadersViewport"
                class="relative overflow-x-auto scroll-smooth"
            >
                <div
                    id="leadersTrack"
                    class="leaders-track flex gap-6"
                >
                @foreach($staffMembers as $index => $staff)
                    @php
                        $leaderTitle = app()->getLocale() === 'am'
                            ? ($staff->title_am ?: $staff->title_en)
                            : ($staff->title_en ?: $staff->title_am);
                        $departmentName = $staff->department?->name;
                        $initials = collect(explode(' ', trim($staff->display_name)))->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->join('');
                    @endphp
                    <article
                        class="leader-card group relative flex-none rounded-[20px] border border-slate-200 bg-white shadow-xl transition duration-500 hover:-translate-y-1 hover:shadow-2xl"
                    >

                        <div class="relative flex min-h-[190px] overflow-hidden rounded-[20px] bg-white">
                            <div class="relative h-full w-1/3 bg-slate-100">
                                @if(!empty($staff->photo_path))
                                    <x-optimized-image
                                        src="{{ asset('storage/' . ltrim($staff->photo_path, '/')) }}"
                                        alt="{{ $staff->display_name }}"
                                        class="h-full w-full object-cover"
                                        ratio="3/4"
                                        maxHeight="100%"
                                    />
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 text-3xl font-semibold text-indigo-600">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex w-2/3 flex-col gap-3 p-3">
                                <div class="space-y-1">
                                    <h4 class="text-xl text-center font-semibold text-slate-900 py-2">{{ $staff->display_name }}</h4>
                                    @if($leaderTitle)
                                        <h3 class="text-xl justify-items-center text-center    text-blue-600 ">
                                            {{ $leaderTitle }}
                                        </h3>
                                    @endif 
                                </div>

                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
      
    </div>
</section>
@endif
@if($organizationSummaries->count())
<section id="organization-stats" class="scroll-section bg-gradient-to-b from-white to-gray-50 py-12 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="flex items-center justify-between mb-12 gap-6">
            <div class="flex items-center gap-3">
                <x-heroicon-o-chart-bar class="h-6 w-6 text-blue-600" aria-hidden="true" />
                <h5 class="text-2xl lg:text-3xl font-bold text-gray-900">
                    <span class=" text-blue-600 ">{{ __('home.public_servant_dashboard.title') }}</span>
                </h5>
            </div>

            <a href="{{ route('public-servants.dashboard') }}"
               class="{{ $viewActionClasses }} hidden lg:inline-flex"
            >
                <span>{{ __('home.news.view_all') }}</span>
                <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="flex flex-wrap justify-between gap-6">
            <div class="group relative flex-1 min-w-[220px] max-w-xs">
                <div class="absolute inset-0 rounded-full border border-transparent bg-gradient-to-br from-blue-400 to-cyan-500 opacity-0 blur transition duration-500 group-hover:opacity-60"></div>
                <div class="relative z-10 flex aspect-square w-full flex-col items-center justify-center gap-4 overflow-hidden rounded-full border border-gray-200 bg-white p-6 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/20">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg">
                        <x-heroicon-s-users class="h-7 w-7" />
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-wide text-blue-600">{{ __('home.public_servant_dashboard.total_label') }}</p>
                        <p class="text-3xl font-semibold text-blue-900">
                            <span class="stat-value" data-stat-target="{{ $citizenTotals['total'] }}">0</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="group relative flex-1 min-w-[220px] max-w-xs">
                <div class="absolute inset-0 rounded-full border border-transparent bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-0 blur transition duration-500 group-hover:opacity-60"></div>
                <div class="relative z-10 flex aspect-square w-full flex-col items-center justify-center gap-4 overflow-hidden rounded-full border border-gray-200 bg-white p-6 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-emerald-500/30">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg">
                        <x-heroicon-s-user class="h-7 w-7" />
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-wide text-emerald-600">{{ __('home.public_servant_dashboard.male_label') }}</p>
                        <p class="text-3xl font-semibold text-emerald-900">
                            <span class="stat-value" data-stat-target="{{ $citizenTotals['male'] }}">0</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="group relative flex-1 min-w-[220px] max-w-xs">
                <div class="absolute inset-0 rounded-full border border-transparent bg-gradient-to-br from-rose-400 to-rose-600 opacity-0 blur transition duration-500 group-hover:opacity-60"></div>
                <div class="relative z-10 flex aspect-square w-full flex-col items-center justify-center gap-4 overflow-hidden rounded-full border border-gray-200 bg-white p-6 text-center shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-rose-500/30">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-600 text-white shadow-lg">
                        <x-heroicon-s-user-circle class="h-7 w-7" />
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-wide text-rose-600">{{ __('home.public_servant_dashboard.female_label') }}</p>
                        <p class="text-3xl font-semibold text-rose-900">
                            <span class="stat-value" data-stat-target="{{ $citizenTotals['female'] }}">0</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<section id="services-section" class="scroll-section bg-gradient-to-b from-blue-900 to-blue-800 text-white py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between mb-16">
            <div class="flex items-center gap-3">
                <x-heroicon-o-cube-transparent class="h-6 w-6 text-indigo-600" aria-hidden="true" />
                <div>
                    @php
                        $servicesHighlight = '<span class="bg-gradient-to-r from-sky-200 to-indigo-200 bg-clip-text text-transparent">' . __('home.services.highlight') . '</span>';
                    @endphp
                    <h5 class="text-5xl lg:text-3xl font-bold text-white">
                       {!! __('home.services.title', ['highlight' => $servicesHighlight]) !!}
                    </h5>
                </div>
            </div>

            <a
                href="{{ route('services.index') }}"
                class="{{ $viewActionClasses }}"
            >
                {{ __('home.services.view_all') }}
            </a>
        </div>

        <div class="relative">
            <div class="overflow-hidden">
                <div
                    id="servicesTrack"
                    class="flex gap-6 overflow-x-auto pb-4 pr-4 lg:pr-0 scroll-smooth"
                >
            @php
                $servicePalettes = [
                    'from-blue-500 to-cyan-500',
                    'from-emerald-500 to-teal-500',
                    'from-purple-500 to-pink-500',
                    'from-orange-500 to-amber-500',
                    'from-sky-500 to-indigo-500',
                    'from-rose-500 to-fuchsia-500',
                ];
            @endphp

            @foreach ($services as $service)
                @php
                    $palette = $servicePalettes[$loop->index % count($servicePalettes)];
                    $requirements = collect(preg_split('/\r?\n/', $service->displayRequirements ?? ''))
                        ->map(fn ($line) => trim($line))
                        ->filter()
                        ->values()
                        ->take(3);
                @endphp

                <div class="charter-card group relative flex-none w-80">
                    <div class="absolute -inset-0.5 bg-gradient-to-r {{ $palette }} rounded-2xl opacity-20 blur transition duration-500 group-hover:opacity-0"></div>

                    <div class="relative flex h-full flex-col rounded-2xl border border-transparent bg-white p-8 shadow-2xl transition-all duration-500 hover:border-gray-200 hover:shadow-sm">
                      

                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $service->display_title }}</h3>
             


                        <div class="mt-auto pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-500">{{ __('common.labels.learn_more') }}</span>
                                <a href="{{ route('services.show', $service->slug) }}"
                                   class="text-blue-600 group-hover:text-blue-800 transition-colors duration-300">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@if($charterDepartments->count())
<section id="citizen-charter-highlight" class="scroll-section bg-gradient-to-b from-gray-50 to-white py-16 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="absolute -top-16 right-0 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute -bottom-16 left-4 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl -z-10"></div>

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between mb-10">
            <div class="flex flex-col gap-4 text-left lg:flex-row lg:items-start lg:gap-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg">
                    <x-heroicon-o-scale class="h-6 w-6 text-indigo-200" aria-hidden="true" />
                </div>
                <div class="space-y-2 max-w-3xl">
               
                    <h4 class="text-3xl lg:text-4xl font-bold text-blue-900">
                        {{ __('public.citizen_charter.overview.heading') }}
                    </h4>
                   
                </div>
            </div>

            <a
                href="{{ route('citizen-charter.index') }}"
                class="{{ $viewActionClasses }}"
            >
                {{ __('public.buttons.view_details') }}
                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" aria-hidden="true" />
            </a>
        </div>

        <div class="relative">
            <div class="overflow-hidden">
                <div
                    id="citizenCharterTrack"
                    class="flex gap-6 overflow-x-auto pb-4 pr-4 lg:pr-0 scroll-smooth"
                >
                    @foreach($charterDepartments as $department)
                        <a
                            href="{{ route('citizen-charter.department', $department) }}"
                            class="charter-card group relative flex-none w-80 overflow-hidden rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                        >
                            <div class="absolute inset-0 rounded-3xl border border-transparent transition duration-500 group-hover:border-blue-200"></div>

                            <div class="relative h-full flex flex-col justify-between space-y-4">
                        

                                <h3 class="text-2xl font-semibold text-gray-900">
                                    {{ $department->display_name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $department->charter_services_count ?? 0 }} {{ __('public.citizen_charter.index.services_count') }}
                                </p>

                                <div class="mt-6 flex justify-center">
                                    <span class="{{ $viewActionClasses }}">
                                        {{ __('public.buttons.view_details') }}
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5l7 7-7 7M18 12H3" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ================= CTA ================= --}}
<section id="cta-section" class="scroll-section relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNlZWYiIGZpbGwtb3BhY2l0eT0iMC40Ij48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIxLjUiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-20"></div>
    </div>

    <div class="absolute top-1/4 left-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-delayed"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
   
        @php
            $ctaHighlight = '<span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">' . __('home.cta.highlight') . '</span>';
        @endphp
        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            {!! __('home.cta.title', ['highlight' => $ctaHighlight]) !!}
        </h2>


    
    </div>
</section>

{{-- ================= LIVE SUPPORT ================= --}}
<section id="live-support" class="scroll-section bg-gradient-to-br from-blue-600 to-indigo-600 py-16 text-white">
    <div class="relative mx-auto max-w-4xl px-6 sm:px-8 lg:px-12 text-center">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIxLjUiLz48L2c+PC9zdmc+')] opacity-10"></div>
        <div class="relative space-y-6">
         
            <h2 class="text-3xl md:text-4xl font-bold">
                {{ __('home.live_support.heading') }}
            </h2>
         
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <form method="POST" action="{{ route('chat.start') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-blue-600 transition hover:bg-blue-50"
                    >
                        {{ __('home.live_support.start_chat') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4 4 4-4m0 0l-4 4-4-4m4 4V5"></path>
                        </svg>
                    </button>
                </form>
                <a
                    href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full border border-white/40 px-6 py-3 text-sm font-semibold text-white/90 transition hover:bg-white/10"
                >
                    {{ __('home.live_support.request_call') }}
                </a>
            </div>
        </div>
    </div>
</section>



{{-- ================= SLIDER & SCROLL SCRIPT ================= --}}
<script>
let currentSlide = 0;
let previousSlideIndex = 0;
const slides = document.querySelectorAll('.hero-slide');
const indicators = document.querySelectorAll('[data-hero-indicator]');
const totalSlides = slides.length;
const enterAnimations = ['heroWaveIn', 'heroGlideIn', 'heroSwirlIn', 'heroDriftIn', 'heroPulseIn'];
const exitAnimations = ['heroWaveOut', 'heroGlideOut', 'heroSwirlOut', 'heroDriftOut', 'heroPulseOut'];
let charterAutoInterval = null;
let charterMotionDirection = 1;
let servicesAutoInterval = null;
let servicesMotionDirection = 1;
let leadersViewportElement = null;
let leadersTrackElement = null;
let leadersAutoInterval = null;
let leadersMotionDirection = 1;
let leadersCardStep = 340;
const leadersAutoDelay = 16;

    slides.forEach(function (slide) {
        slide.addEventListener('animationend', function (event) {
            if (enterAnimations.includes(event.animationName)) {
                slide.classList.remove('slide-enter');
            } else if (exitAnimations.includes(event.animationName)) {
                slide.classList.remove('slide-exit');
            }
        });
    });

    function showSlide(index) {
        if (!slides[index]) return;

        slides.forEach(function (slide) {
            slide.classList.remove('opacity-100', 'z-10', 'slide-active');
            slide.classList.add('opacity-0', 'z-0');
        });
        const outgoing = slides[previousSlideIndex];
        if (outgoing && outgoing !== slides[index]) {
            outgoing.classList.remove('slide-enter', 'slide-active');
            outgoing.classList.add('slide-exit');
        }

        slides[index].classList.remove('opacity-0', 'z-0');
        slides[index].classList.add('opacity-100', 'z-10');
        slides[index].classList.add('slide-active', 'slide-enter');

        document.querySelectorAll('[onclick^="goToSlide"]').forEach(function (btn, i) {
            btn.classList.toggle('bg-white', i === index);
            btn.classList.toggle('bg-white/50', i !== index);
        });

        indicators.forEach(function (btn, i) {
            btn.classList.toggle('indicator-active', i === index);
        });

        previousSlideIndex = index;
        currentSlide = index;
    }

    function sliderNext() {
        if (totalSlides < 2) return;
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    function sliderPrev() {
        if (totalSlides < 2) return;
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }

    function goToSlide(index) {
        currentSlide = index;
        showSlide(currentSlide);
    }

    showSlide(currentSlide);

    if (totalSlides > 1) {
        setInterval(sliderNext, 7000);
    }

    function toggleOfficialMessage() {
        const textEl = document.getElementById('officialMessageText');
        const btnEl = document.getElementById('officialMessageToggle');
        const fadeEl = document.getElementById('messageFade');

        if (!textEl || !btnEl) return;

        const expanded = textEl.getAttribute('data-expanded') === 'true';
        const readMoreText = btnEl.dataset.readMore || 'Read more';
        const readLessText = btnEl.dataset.readLess || 'Read less';
        const labelEl = btnEl.querySelector('.official-message-label');
        const iconPath = btnEl.querySelector('svg path');
        const downArrowPath = 'M19 9l-7 7-7-7';
        const upArrowPath = 'M5 15l7-7 7 7';

        if (expanded) {
            textEl.style.display = '-webkit-box';
            textEl.style.webkitLineClamp = '7';
            textEl.style.webkitBoxOrient = 'vertical';
            textEl.classList.add('overflow-hidden');
            textEl.setAttribute('data-expanded', 'false');
            if (labelEl) {
                labelEl.textContent = readMoreText;
            }
            if (iconPath) {
                iconPath.setAttribute('d', downArrowPath);
            }
            if (fadeEl) fadeEl.style.opacity = '1';
        } else {
            textEl.style.display = 'block';
            textEl.style.webkitLineClamp = 'unset';
            textEl.style.webkitBoxOrient = 'unset';
            textEl.classList.remove('overflow-hidden');
            textEl.setAttribute('data-expanded', 'true');
            if (labelEl) {
                labelEl.textContent = readLessText;
            }
            if (iconPath) {
                iconPath.setAttribute('d', upArrowPath);
            }
            if (fadeEl) fadeEl.style.opacity = '0';
        }
    }

    // ========= Scroll reveal + Leaders auto scroll =========
    function initScrollSections() {
        var sections = document.querySelectorAll('.scroll-section');
        var supportsIntersectionObserver = typeof IntersectionObserver !== 'undefined';
        var observer = null;

        if (supportsIntersectionObserver) {
            observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2
            });
        }

        sections.forEach(function (section) {
            if (section.classList.contains('is-visible')) {
                return;
            }
            if (observer) {
                observer.observe(section);
            } else {
                section.classList.add('is-visible');
            }
        });

        // Leaders autoplay slider (same pattern as services slider)
        leadersViewportElement = document.getElementById('leadersViewport');
        leadersTrackElement = document.getElementById('leadersTrack');
        if (leadersViewportElement && leadersTrackElement) {
            requestAnimationFrame(function () {
                updateLeaderSliderLayout();
                startLeaderAuto();
            });

            function pauseLeaders() {
                stopLeaderAuto();
            }

            function resumeLeaders() {
                startLeaderAuto();
            }

            leadersViewportElement.addEventListener('mouseenter', pauseLeaders);
            leadersViewportElement.addEventListener('mouseleave', resumeLeaders);
            leadersViewportElement.addEventListener('touchstart', pauseLeaders, { passive: true });
            leadersViewportElement.addEventListener('touchend', resumeLeaders, { passive: true });
            leadersViewportElement.addEventListener('focusin', pauseLeaders);
            leadersViewportElement.addEventListener('focusout', resumeLeaders);
            window.addEventListener('resize', function () {
                updateLeaderSliderLayout();
            });
        }

        const charterTrack = document.getElementById('citizenCharterTrack');
        if (charterTrack) {
            function startCharterMotion() {
                if (charterAutoInterval) {
                    return;
                }
                charterAutoInterval = setInterval(function () {
                    const maxScrollLeft = charterTrack.scrollWidth - charterTrack.clientWidth;
                    if (maxScrollLeft <= 0) {
                        return;
                    }

                    const step = 2.0 * charterMotionDirection;
                    const next = charterTrack.scrollLeft + step;

                    if (next >= maxScrollLeft) {
                        charterTrack.scrollLeft = maxScrollLeft;
                        charterMotionDirection = -1;
                    } else if (next <= 0) {
                        charterTrack.scrollLeft = 0;
                        charterMotionDirection = 1;
                    } else {
                        charterTrack.scrollLeft = next;
                    }
                }, 16);
            }

            function stopCharterMotion() {
                if (!charterAutoInterval) {
                    return;
                }
                clearInterval(charterAutoInterval);
                charterAutoInterval = null;
            }

            startCharterMotion();

            charterTrack.addEventListener('mouseenter', stopCharterMotion);
            charterTrack.addEventListener('mouseleave', startCharterMotion);
            charterTrack.addEventListener('touchstart', stopCharterMotion, { passive: true });
            charterTrack.addEventListener('touchend', startCharterMotion, { passive: true });
        }

        const servicesTrack = document.getElementById('servicesTrack');
        if (servicesTrack) {
            function startServicesMotion() {
                if (servicesAutoInterval) {
                    return;
                }
                servicesAutoInterval = setInterval(function () {
                    const maxScrollLeft = servicesTrack.scrollWidth - servicesTrack.clientWidth;
                    if (maxScrollLeft <= 0) {
                        return;
                    }

                    const step = 2.2 * servicesMotionDirection;
                    const next = servicesTrack.scrollLeft + step;

                    if (next >= maxScrollLeft) {
                        servicesTrack.scrollLeft = maxScrollLeft;
                        servicesMotionDirection = -1;
                    } else if (next <= 0) {
                        servicesTrack.scrollLeft = 0;
                        servicesMotionDirection = 1;
                    } else {
                        servicesTrack.scrollLeft = next;
                    }
                }, 16);
            }

            function stopServicesMotion() {
                if (!servicesAutoInterval) {
                    return;
                }
                clearInterval(servicesAutoInterval);
                servicesAutoInterval = null;
            }

            startServicesMotion();
            servicesTrack.addEventListener('mouseenter', stopServicesMotion);
            servicesTrack.addEventListener('mouseleave', startServicesMotion);
            servicesTrack.addEventListener('touchstart', stopServicesMotion, { passive: true });
            servicesTrack.addEventListener('touchend', startServicesMotion, { passive: true });
        }

        animateStatCounters();

        // Official message "Read more" button visibility
        const textEl = document.getElementById('officialMessageText');
        const btnEl = document.getElementById('officialMessageToggle');
        if (textEl && btnEl) {
            requestAnimationFrame(function () {
                const isClipped = textEl.scrollHeight > textEl.clientHeight + 2;
                if (isClipped) {
                    btnEl.classList.remove('hidden');
                    btnEl.style.animation = 'fade-in-up 0.5s ease-out forwards';
                    btnEl.style.opacity = '0';
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollSections);
    } else {
        initScrollSections();
    }

    function goToLeaderSlide(direction) {
        if (!leadersViewportElement) {
            return;
        }
        const delta = direction === 'next' ? leadersCardStep : -leadersCardStep;
        const maxScrollLeft = leadersViewportElement.scrollWidth - leadersViewportElement.clientWidth;
        const target = leadersViewportElement.scrollLeft + delta;
        if (target >= maxScrollLeft) {
            leadersViewportElement.scrollLeft = maxScrollLeft;
            leadersMotionDirection = -1;
        } else if (target <= 0) {
            leadersViewportElement.scrollLeft = 0;
            leadersMotionDirection = 1;
        } else {
            leadersViewportElement.scrollTo({ left: target, behavior: 'smooth' });
        }
        restartLeaderAuto();
    }

    function updateLeaderSliderLayout() {
        if (!leadersTrackElement) {
            return;
        }
        const firstCard = leadersTrackElement.querySelector('.leader-card');
        if (!firstCard) {
            return;
        }
        const computed = window.getComputedStyle(leadersTrackElement);
        const gapValue = computed.gap || computed.columnGap || '0px';
        const gap = parseFloat(gapValue) || 0;
        leadersCardStep = firstCard.getBoundingClientRect().width + gap;
    }

    function startLeaderAuto() {
        if (!leadersViewportElement || leadersAutoInterval) {
            return;
        }
        leadersAutoInterval = setInterval(function () {
            const maxScrollLeft = leadersViewportElement.scrollWidth - leadersViewportElement.clientWidth;
            if (maxScrollLeft <= 0) {
                return;
            }

            const step = 1.6 * leadersMotionDirection;
            const next = leadersViewportElement.scrollLeft + step;

            if (next >= maxScrollLeft) {
                leadersViewportElement.scrollLeft = maxScrollLeft;
                leadersMotionDirection = -1;
            } else if (next <= 0) {
                leadersViewportElement.scrollLeft = 0;
                leadersMotionDirection = 1;
            } else {
                leadersViewportElement.scrollLeft = next;
            }
        }, leadersAutoDelay);
    }

    function stopLeaderAuto() {
        if (!leadersAutoInterval) return;
        clearInterval(leadersAutoInterval);
        leadersAutoInterval = null;
    }

    function restartLeaderAuto() {
        stopLeaderAuto();
        startLeaderAuto();
    }

    function animateStatCounters() {
        const stats = document.querySelectorAll('[data-stat-target]');
        if (!stats.length) return;

        const locale = document.documentElement.lang || 'en';
        const formatter = new Intl.NumberFormat(locale);

        stats.forEach(function (el) {
            const target = parseInt(el.dataset.statTarget, 10) || 0;
            const duration = 1600;
            const startTime = performance.now();

            function tick(now) {
                const progress = Math.min((now - startTime) / duration, 1);
                const value = Math.round(target * progress);
                el.textContent = formatter.format(value);

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    el.textContent = formatter.format(target);
                }
            }

            el.textContent = formatter.format(0);
            requestAnimationFrame(tick);
        });
    }
</script>

<style>
    html {
        scroll-behavior: smooth;
    }

    /* Scroll section base state */
    .scroll-section {
        opacity: 0;
        transform: translateY(32px);
        transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }

    .scroll-section.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Leaders section slider adjustments */
    #leadersTrack {
        display: flex;
        gap: 1.5rem;
        align-items: stretch;
        padding: 1.25rem 0;
    }

    #leadersTrack .leader-card {
        flex: 0 0 auto;
        width: min(420px, 95vw);
        min-width: min(420px, 95vw);
    }

    @media (max-width: 1024px) {
        #leadersTrack {
            gap: 1rem;
        }

        #leadersTrack .leader-card {
            width: min(360px, 92vw);
            min-width: min(360px, 92vw);
        }
    }

    @media (max-width: 640px) {
        #leadersTrack .leader-card {
            width: min(320px, 90vw);
            min-width: min(320px, 90vw);
        }
    }

    /* Leaders section cards: smooth reveal */
    #leaders-section .leader-card {
        opacity: 0;
        transform: translateY(24px);
        transition:
            opacity 0.6s ease-out,
            transform 0.6s ease-out;
    }

    #leaders-section.is-visible .leader-card {
        opacity: 1;
        transform: translateY(0);
    }

    /* Hide horizontal scrollbar visually for leaders viewport */
    #leadersViewport {
        scrollbar-width: none; /* Firefox */
    }
    #leadersViewport::-webkit-scrollbar {
        display: none; /* Chrome, Safari */
    }

    #citizenCharterTrack {
        scrollbar-width: none;
    }
    #citizenCharterTrack::-webkit-scrollbar {
        display: none;
    }

    #servicesTrack {
        scrollbar-width: none;
    }
    #servicesTrack::-webkit-scrollbar {
        display: none;
    }

    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    @keyframes float-delayed {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(10px); }
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-float-delayed { animation: float-delayed 8s ease-in-out infinite; }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .hero-slide {
        transform-origin: center;
        filter: brightness(0.8) saturate(0.9);
        will-change: opacity, transform, filter;
    }

    .hero-slide.slide-active {
        filter: brightness(1.05) saturate(1.15);
    }

    .hero-slide.motion-wave.slide-enter {
        animation: heroWaveIn 1.2s cubic-bezier(0.25, 0.7, 0.35, 1) forwards;
    }

    .hero-slide.motion-wave.slide-exit {
        animation: heroWaveOut 0.95s ease-in-out forwards;
    }

    .hero-slide.motion-drift.slide-enter {
        animation: heroDriftIn 1.1s cubic-bezier(0.25, 0.8, 0.4, 1) forwards;
    }

    .hero-slide.motion-drift.slide-exit {
        animation: heroDriftOut 0.85s cubic-bezier(0.4, 0.2, 0.3, 1) forwards;
    }

    .hero-slide.motion-pulse.slide-enter {
        animation: heroPulseIn 1s cubic-bezier(0.2, 0.6, 0.4, 1) forwards;
    }

    .hero-slide.motion-pulse.slide-exit {
        animation: heroPulseOut 0.75s cubic-bezier(0.4, 0.2, 0.3, 1) forwards;
    }
    .hero-slide.motion-glide.slide-enter {
        animation: heroGlideIn 1.05s cubic-bezier(0.22, 0.6, 0.36, 1) forwards;
    }

    @keyframes heroDriftIn {
        0% {
            opacity: 0;
            transform: translateX(-60px) translateY(20px) scale(0.96);
            filter: blur(10px);
        }
        60% {
            opacity: 0.9;
            transform: translateX(-18px) translateY(6px) scale(0.99);
            filter: blur(4px);
        }
        100% {
            opacity: 1;
            transform: translateX(0) translateY(0) scale(1);
            filter: blur(0);
        }
    }

    @keyframes heroDriftOut {
        0% {
            opacity: 1;
            transform: translateX(0) translateY(0) scale(1);
            filter: blur(0);
        }
        70% {
            opacity: 0.65;
            transform: translateX(30px) translateY(6px) scale(1.02);
            filter: blur(6px);
        }
        100% {
            opacity: 0;
            transform: translateX(70px) translateY(20px) scale(1.04);
            filter: blur(12px);
        }
    }

    @keyframes heroPulseIn {
        0% {
            opacity: 0;
            transform: scale(0.9);
            filter: blur(12px);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.05);
            filter: blur(6px);
        }
        100% {
            opacity: 1;
            transform: scale(1);
            filter: blur(0);
        }
    }

    @keyframes heroPulseOut {
        0% {
            opacity: 1;
            transform: scale(1);
            filter: blur(0);
        }
        60% {
            opacity: 0.7;
            transform: scale(1.02);
            filter: blur(4px);
        }
        100% {
            opacity: 0;
            transform: scale(0.95);
            filter: blur(10px);
        }
    }

    .hero-slide.motion-glide.slide-exit {
        animation: heroGlideOut 0.85s cubic-bezier(0.4, 0.2, 0.3, 1) forwards;
    }

    .hero-slide.motion-swirl.slide-enter {
        animation: heroSwirlIn 1.15s cubic-bezier(0.22, 0.65, 0.36, 1) forwards;
    }

    .hero-slide.motion-swirl.slide-exit {
        animation: heroSwirlOut 0.9s cubic-bezier(0.4, 0.2, 0.3, 1) forwards;
    }

    @keyframes heroWaveIn {
        0% {
            opacity: 0;
            transform: translateX(-80px) translateZ(0) scale(0.97);
            filter: blur(16px);
        }
        55% {
            opacity: 0.85;
            transform: translateX(-30px) translateZ(0) scale(0.99);
            filter: blur(8px);
        }
        100% {
            opacity: 1;
            transform: translateX(0) translateZ(0) scale(1);
            filter: blur(0);
        }
    }

    @keyframes heroWaveOut {
        0% {
            opacity: 1;
            transform: translateX(0) scale(1);
            filter: blur(0);
        }
        45% {
            opacity: 0.7;
            transform: translateX(20px) scale(1.02);
            filter: blur(4px);
        }
        100% {
            opacity: 0;
            transform: translateX(60px) scale(1.04);
            filter: blur(12px);
        }
    }

    @keyframes heroGlideIn {
        0% {
            opacity: 0;
            transform: translateX(-70px) scale(0.96);
            filter: blur(14px);
        }
        50% {
            opacity: 0.8;
            transform: translateX(-25px) scale(0.99);
            filter: blur(6px);
        }
        100% {
            opacity: 1;
            transform: translateX(0) scale(1);
            filter: blur(0);
        }
    }

    @keyframes heroGlideOut {
        0% {
            opacity: 1;
            transform: translateX(0) scale(1);
            filter: blur(0);
        }
        55% {
            opacity: 0.7;
            transform: translateX(30px) scale(1.01);
            filter: blur(6px);
        }
        100% {
            opacity: 0;
            transform: translateX(70px) scale(1.03);
            filter: blur(12px);
        }
    }

    @keyframes heroSwirlIn {
        0% {
            opacity: 0;
            transform: translate3d(-60px, 42px, 0) scale(0.95) rotate(6deg);
            filter: blur(18px);
        }
        60% {
            opacity: 0.75;
            transform: translate3d(-20px, 12px, 0) scale(0.98) rotate(2deg);
            filter: blur(6px);
        }
        100% {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1) rotate(0);
            filter: blur(0);
        }
    }

    @keyframes heroSwirlOut {
        0% {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1) rotate(0);
            filter: blur(0);
        }
        50% {
            opacity: 0.6;
            transform: translate3d(-18px, -10px, 0) scale(1.01) rotate(-3deg);
            filter: blur(6px);
        }
        100% {
            opacity: 0;
            transform: translate3d(-52px, -35px, 0) scale(1.03) rotate(-8deg);
            filter: blur(14px);
        }
    }

    .hero-indicator {
        transition: transform 0.45s ease, box-shadow 0.45s ease, background 0.45s ease;
        background: rgba(255, 255, 255, 0.65);
    }

    .hero-indicator.indicator-active {
        background: #ffffff;
        box-shadow: 0 0 12px rgba(59, 130, 246, 0.45);
        transform: scale(1.35);
    }
</style>
</div>
@endsection
