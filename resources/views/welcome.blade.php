@extends('layouts.public')

@section('title', config('app.name'))

@section('content')
@php
    use App\Models\HomeSlide;
    use App\Models\Post;
    use App\Models\Staff;

    $slides = HomeSlide::query()
        ->active()
        ->ordered()
        ->get();

    $officialMessage = \App\Models\OfficialMessage::query()
        ->where('is_active', true)
        ->first();

    $officialInitials = $officialMessage
        ? collect(explode(' ', trim($officialMessage->name)))
            ->filter()
            ->take(2)
            ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
            ->join('')
        : '';

    $latestNews = Post::query()
        ->whereNotNull('published_at')
        ->orderByDesc('published_at')
        ->take(6)
        ->get();

    $staffMembers = Staff::query()
        ->where('is_active', true)
        ->where('is_featured', true) // only department leadership / highlighted staff
        ->orderBy('sort_order')
        ->take(6)
        ->get();

    $type = 'news';
@endphp

{{-- ================= HERO SLIDER (TITLE/SUBTITLE BOTTOM) ================= --}}
<section class="relative w-full bg-gray-900 overflow-hidden">
    <div id="heroSlider" class="relative w-full h-[60vh] min-h-[450px] max-h-[550px] overflow-hidden">
        @forelse ($slides as $index => $slide)
            <div
                class="hero-slide absolute inset-0 transition-all duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
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
                    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12 w-full pb-10">
                        <div class="max-w-3xl">
                            <h1 class="text-2xl md:text-3xl font-bold text-orange-500 justify-center leading-tight">
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
                        Official digital public service platform.
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
                aria-label="Previous slide"
            >
                ‹
            </button>

            <button
                type="button"
                onclick="sliderNext()"
                class="group absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 text-white text-xl transition-all duration-300 transform hover:scale-110 active:scale-95 z-20"
                aria-label="Next slide"
            >
                ›
            </button>

            {{-- Slide indicators --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                @foreach($slides as $index => $slide)
                    <button
                        type="button"
                        onclick="goToSlide({{ $index }})"
                        class="w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white' : 'bg-white/50 hover:bg-white/80' }}"
                        aria-label="Go to slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ================= OFFICIAL MESSAGE WITH MODERN DESIGN ================= --}}
@if($officialMessage)
<section class="bg-gradient-to-b from-white to-gray-50 border-t">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-stretch">
            {{-- PHOTO CARD WITH GLASS EFFECT --}}
            <div class="lg:col-span-1 lg:order-last">
                <div class="relative h-full rounded-3xl overflow-hidden group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl blur opacity-20 group-hover:opacity-30 transition duration-1000"></div>

                    <div class="relative h-full rounded-3xl overflow-hidden bg-gradient-to-br from-gray-100 to-white ring-1 ring-gray-200/50 backdrop-blur-sm">
                        @if($officialMessage->photo_path)
                            <img
                                src="{{ asset('storage/' . $officialMessage->photo_path) }}"
                                alt="{{ $officialMessage->name }}"
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
                                    <p class="text-gray-600 font-medium">Official Portrait</p>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- MESSAGE CONTENT --}}
            <div class="lg:col-span-2">
                <div class="h-full rounded-3xl border border-gray-200/50 bg-gradient-to-br from-white to-gray-50/50 p-8 lg:p-10 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all duration-500">

                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">
                        Message from the <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Higher Official</span>
                    </h2>

                    <div class="mt-8 flex-1 relative">
                        <div
                            id="officialMessageText"
                            class="text-gray-700 leading-relaxed whitespace-pre-line text-justify overflow-hidden transition-all duration-500 ease-out"
                            style="display:-webkit-box;-webkit-line-clamp:7;-webkit-box-orient:vertical;"
                            data-expanded="false"
                        >
                            {{ $officialMessage->message }}
                        </div>

                        <div id="messageFade" class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent pointer-events-none transition-opacity duration-500"></div>

                        <button
                            id="officialMessageToggle"
                            type="button"
                            class="mt-8 hidden group inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 font-semibold hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 transform hover:-translate-y-0.5 ring-1 ring-blue-100"
                            onclick="toggleOfficialMessage()"
                        >
                            <span class="group-hover:translate-y-[-1px] transition-transform">Read more</span>
                            <svg class="w-4 h-4 group-hover:translate-y-[-1px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-bold text-gray-900 text-xl">{{ $officialMessage->name }}</div>
                                <div class="text-gray-600 mt-1">{{ $officialMessage->title }}</div>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ now()->format('F d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ================= LATEST NEWS WITH MODERN CARDS ================= --}}
@if($latestNews->count())
<section class="bg-gradient-to-b from-gray-50 to-white py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl -z-10"></div>

        <div class="flex items-center justify-between mb-12">
            <div>
              
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">News</span>
                </h2>
              
            </div>

            <a href="{{ url('/news') }}"
               class="group hidden lg:flex items-center gap-3 px-6 py-3 rounded-xl bg-gradient-to-r from-white to-gray-50 text-gray-700 font-semibold hover:text-blue-700 transition-all duration-300 transform hover:-translate-y-1 ring-1 ring-gray-200 hover:ring-blue-200">
                <span>View all</span>
                <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($latestNews as $post)
                <a
                    href="{{ $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug) }}"
                    class="group relative flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-500 hover:-translate-y-2 hover:border-transparent hover:shadow-2xl hover:shadow-blue-500/10"
                >
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl opacity-0 group-hover:opacity-10 blur transition duration-500"></div>

                    @if ($post->cover_image_path)
                        <div class="relative mb-6 h-48 w-full rounded-xl overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $post->cover_image_path) }}"
                                alt="{{ $post->display_title }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                loading="lazy"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                            <div class="absolute top-4 left-4 px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700">
                                News
                            </div>
                        </div>
                    @endif

                    <div class="relative flex-1">
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors duration-300">
                            {{ $post->display_title }}
                        </h3>

                        <p class="mt-3 text-gray-600 line-clamp-3">
                            {{ $post->display_excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->display_body), 140) }}
                        </p>

                        <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-500">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : __('common.labels.recently_updated') }}
                                </span>
                            </div>
                            <div class="text-blue-600 group-hover:translate-x-2 transition-transform duration-300">
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
                View All News & Announcements
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ================= SERVICES OVERVIEW WITH INTERACTIVE CARDS ================= --}}
{{-- ================= MEET OUR LEADERS WITH ANIMATED CARDS ================= --}}
@if($staffMembers->count())
<section class="bg-gradient-to-b from-white to-gray-50 py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl -z-10"></div>
        
        <div class="flex items-center justify-between mb-12">
            <div>
             
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">
                    Meet Our <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Leaders</span>
                </h2>
                <p class="mt-3 text-gray-600 max-w-2xl">
                    Meet the dedicated team providing leadership and direction for our public services.
                </p>
            </div>
        </div>

        <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach($staffMembers as $index => $staff)
                <div 
                    class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500 hover:-translate-y-2"
                    style="animation-delay: {{ $index * 100 }}ms; animation: fade-in-up 0.6s ease-out {{ $index * 100 }}ms both;"
                >
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl opacity-0 group-hover:opacity-10 blur transition duration-500"></div>
                    
                    <div class="relative flex flex-col md:flex-row h-full">
                        {{-- Photo Container --}}
                        <div class="relative w-full md:w-40 h-48 md:h-auto overflow-hidden flex-shrink-0">
                            @if(!empty($staff->photo_path))
                                <img 
                                    src="{{ asset('storage/' . ltrim($staff->photo_path, '/')) }}" 
                                    alt="{{ $staff->display_name }}" 
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 group-hover:from-indigo-100 group-hover:to-purple-100 transition-all duration-500">
                                    @php
                                        $initials = collect(explode(' ', trim($staff->display_name)))->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->join('');
                                    @endphp
                                    <div class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent transform group-hover:scale-110 transition-transform duration-500">
                                        {{ $initials }}
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Corner accent --}}
                            <div class="absolute top-0 left-0 w-12 h-12">
                                <div class="absolute top-3 left-3 w-6 h-6 border-t-2 border-l-2 border-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </div>
                        </div>

                        {{-- Content Container --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-700 transition-colors duration-300">
                                    {{ $staff->display_name }}
                                </h3>
                          

                                @if(!empty($staff->display_bio))
                                    <p class="mt-4 text-gray-600 text-sm leading-relaxed line-clamp-3">
                                        {{ strip_tags($staff->display_bio) }}
                                    </p>
                                @endif
                            </div>

                          
                        </div>
                    </div>
                    
                    {{-- Hover border effect --}}
                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-indigo-200 transition-all duration-500 pointer-events-none"></div>
                </div>
            @endforeach
        </div>
        
        {{-- View All Button --}}
        <div class="mt-12 text-center">
            <a href="{{ route('staff.index') }}"
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/30">
                <span>View All </span>
                <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<section class="bg-gradient-to-b from-white to-gray-50 py-10 overflow-hidden">
    <div class="relative mx-auto max-w-full lg:max-w-screen-2xl w-full px-6 sm:px-8 lg:px-12">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 text-sm font-semibold mb-6 ring-1 ring-blue-100">
                Our Services
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Public <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Services</span> & Information
            </h2>
            <p class="text-xl text-gray-600">
                Access official services, submit requests, and stay informed through our secure digital platform.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $services = [
                    [
                        'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                        'title' => 'Online Services',
                        'description' => 'Submit service and document requests online and track their progress in real time with our transparent tracking system.',
                        'color' => 'from-blue-500 to-cyan-500',
                        'features' => ['Online Applications', 'Real-time Tracking', 'Digital Payments']
                    ],
                    [
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                        'title' => 'Transparency',
                        'description' => 'Access official announcements, documents, tenders, and institutional updates in our public portal.',
                        'color' => 'from-emerald-500 to-teal-500',
                        'features' => ['Public Documents', 'Tender Updates', 'Official Reports']
                    ],
                    [
                        'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z',
                        'title' => 'Communication',
                        'description' => 'Contact departments, submit feedback, and receive responses through our secure communication channels.',
                        'color' => 'from-purple-500 to-pink-500',
                        'features' => ['Direct Contact', 'Secure Feedback', 'Quick Responses']
                    ]
                ];
            @endphp

            @foreach ($services as $service)
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r {{ $service['color'] }} rounded-2xl opacity-0 group-hover:opacity-20 blur transition duration-500"></div>

                    <div class="relative h-full rounded-2xl border border-gray-200 bg-white p-8 transition-all duration-500 hover:border-transparent hover:shadow-2xl">
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br {{ $service['color'] }} shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $service['title'] }}</h3>
                        <p class="text-gray-600 mb-6">{{ $service['description'] }}</p>

                        <ul class="space-y-3">
                            @foreach ($service['features'] as $feature)
                                <li class="flex items-center gap-3 text-gray-700">
                                    <div class="w-2 h-2 rounded-full bg-gradient-to-r {{ $service['color'] }}"></div>
                                    <span class="text-sm font-medium">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-500">Learn more</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-2 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= CTA WITH ANIMATIONS ================= --}}
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNlZWYiIGZpbGwtb3BhY2l0eT0iMC40Ij48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIxLjUiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-20"></div>
    </div>

    <div class="absolute top-1/4 left-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-delayed"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm text-blue-700 text-sm font-semibold mb-6 ring-1 ring-blue-100 shadow-sm">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
            Serving The Public
        </div>

        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Digital Services for <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Better Governance</span>
        </h2>

        <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-10">
            Our platform is officially maintained to improve accessibility, efficiency, and transparency in public service delivery.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('services.index') }}"
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/30">
                Explore Services
                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </a>

            <a href="{{ route('login') }}"
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 rounded-xl bg-white/80 backdrop-blur-sm text-gray-700 font-semibold hover:text-blue-700 transition-all duration-300 transform hover:-translate-y-1 ring-1 ring-gray-200 hover:ring-blue-200">
                Staff Portal
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ================= SLIDER SCRIPT (FIXED) ================= --}}
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide) => {
            slide.classList.remove('opacity-100', 'z-10');
            slide.classList.add('opacity-0', 'z-0');
        });

        slides[index].classList.remove('opacity-0', 'z-0');
        slides[index].classList.add('opacity-100', 'z-10');

        document.querySelectorAll('[onclick^="goToSlide"]').forEach((btn, i) => {
            btn.classList.toggle('bg-white', i === index);
            btn.classList.toggle('bg-white/50', i !== index);
        });
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

    if (totalSlides > 1) {
        setInterval(sliderNext, 7000);
    }

    function toggleOfficialMessage() {
        const textEl = document.getElementById('officialMessageText');
        const btnEl = document.getElementById('officialMessageToggle');
        const fadeEl = document.getElementById('messageFade');

        if (!textEl || !btnEl) return;

        const expanded = textEl.getAttribute('data-expanded') === 'true';

        if (expanded) {
            textEl.style.display = '-webkit-box';
            textEl.style.webkitLineClamp = '7';
            textEl.style.webkitBoxOrient = 'vertical';
            textEl.classList.add('overflow-hidden');
            textEl.setAttribute('data-expanded', 'false');
            btnEl.innerHTML = '<span class="group-hover:translate-y-[-1px] transition-transform">Read more</span><svg class="w-4 h-4 group-hover:translate-y-[-1px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
            if (fadeEl) fadeEl.style.opacity = '1';
        } else {
            textEl.style.display = 'block';
            textEl.style.webkitLineClamp = 'unset';
            textEl.style.webkitBoxOrient = 'unset';
            textEl.classList.remove('overflow-hidden');
            textEl.setAttribute('data-expanded', 'true');
            btnEl.innerHTML = '<span class="group-hover:translate-y-[-1px] transition-transform">Read less</span><svg class="w-4 h-4 group-hover:translate-y-[-1px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>';
            if (fadeEl) fadeEl.style.opacity = '0';
        }
    }

    (function () {
        const textEl = document.getElementById('officialMessageText');
        const btnEl = document.getElementById('officialMessageToggle');
        if (!textEl || !btnEl) return;

        requestAnimationFrame(() => {
            const isClipped = textEl.scrollHeight > textEl.clientHeight + 2;
            if (isClipped) {
                btnEl.classList.remove('hidden');
                btnEl.style.animation = 'fade-in-up 0.5s ease-out forwards';
                btnEl.style.opacity = '0';
            }
        });
    })();
</script>

<style>
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
</style>
@endsection
