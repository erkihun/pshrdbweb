@extends('layouts.public')

@php
    $pageTitle = $seoMeta['title'] ?? __('public.sitemap_page.title');
    $pageDescription = $seoMeta['description'] ?? __('public.sitemap_page.description');
@endphp

@section('content')
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand-gold">
                    {{ __('public.footer.sitemap') }}
                </p>
                <h1 class="mt-3 text-3xl font-bold text-slate-900 sm:text-4xl">
                    {{ $pageTitle }}
                </h1>
                <p class="mt-3 text-base text-slate-500 sm:text-lg md:text-xl">
                    {{ $pageDescription }}
                </p>
                <div class="mt-6 flex items-center justify-center gap-4 text-sm text-gray-500">
                    <span class="rounded-full bg-slate-200/70 px-3 py-1 uppercase tracking-wide text-xs font-semibold text-slate-600">
                        {{ __('public.sitemap_page.sections.general') }}
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="rounded-full bg-slate-200/70 px-3 py-1 uppercase tracking-wide text-xs font-semibold text-slate-600">
                        {{ __('public.sitemap_page.sections.citizen_charter') }}
                    </span>
                </div>
            </div>

            @if(empty($sections))
                <div class="mt-12 rounded-2xl border border-slate-200 bg-white p-8 shadow">
                    <p class="text-center text-sm text-slate-500">
                        {{ __('public.sitemap_page.description') }}
                    </p>
                </div>
            @else
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($sections as $section)
                        <article class="rounded-3xl border border-slate-200 bg-white/70 p-6 shadow-lg shadow-slate-200/40 transition hover:shadow-xl">
                            <header>
                                <h2 class="text-lg font-semibold text-slate-900">
                                    {{ $section['title'] }}
                                </h2>
                                @if(!empty($section['description']))
                                    <p class="mt-2 text-sm text-slate-500">
                                        {{ $section['description'] }}
                                    </p>
                                @endif
                            </header>

                            @if(!empty($section['links']))
                                <ul class="mt-5 space-y-3">
                                    @foreach($section['links'] as $link)
                                        <li>
                                            <a
                                                href="{{ $link['url'] }}"
                                                class="flex items-center justify-between gap-3 text-sm text-slate-600 transition hover:text-brand-blue"
                                            >
                                                <span>{{ $link['label'] }}</span>
                                                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4 text-slate-400 transition group-hover:text-brand-blue" />
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($section['groups']))
                                <div class="mt-6 space-y-5">
                                    @foreach($section['groups'] as $group)
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50/40 p-4">
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $group['title'] }}
                                            </p>
                                            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                                                @foreach($group['links'] as $link)
                                                    <li>
                                                        <a
                                                            href="{{ $link['url'] }}"
                                                            class="inline-flex items-center justify-between gap-2 hover:text-brand-blue"
                                                        >
                                                            <span>{{ $link['label'] }}</span>
                                                            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m0 0l-6-6m6 6l-6 6" />
                                                            </svg>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
