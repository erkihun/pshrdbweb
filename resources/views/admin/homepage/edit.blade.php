@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('common.labels.homepage') }}</h1>
            <p class="text-sm text-slate-500">{{ __('common.actions.update') }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.homepage.update') }}"
            enctype="multipart/form-data"
            class="space-y-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf
            @method('PUT')

            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Hero</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="hero_title">{{ __('common.labels.title') }}</label>
                        <input
                            id="hero_title"
                            name="hero[title]"
                            type="text"
                            value="{{ old('hero.title', $sections['home.hero']['title'] ?? '') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="hero_cta_text">CTA Text</label>
                        <input
                            id="hero_cta_text"
                            name="hero[cta_text]"
                            type="text"
                            value="{{ old('hero.cta_text', $sections['home.hero']['cta_text'] ?? '') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="hero_subtitle">Subtitle</label>
                    <textarea
                        id="hero_subtitle"
                        name="hero[subtitle]"
                        rows="3"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >{{ old('hero.subtitle', $sections['home.hero']['subtitle'] ?? '') }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="hero_cta_url">CTA URL</label>
                        <input
                            id="hero_cta_url"
                            name="hero[cta_url]"
                            type="text"
                            value="{{ old('hero.cta_url', $sections['home.hero']['cta_url'] ?? '') }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700" for="hero_background">Background Image</label>
                        <input
                            id="hero_background"
                            name="hero[background_image]"
                            type="file"
                            accept="image/*"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                        >
                        @if (!empty($sections['home.hero']['background_image']))
                            <p class="mt-2 text-xs text-slate-500">Current: {{ $sections['home.hero']['background_image'] }}</p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Services Highlight</h2>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="services_title">{{ __('common.labels.title') }}</label>
                    <input
                        id="services_title"
                        name="services[title]"
                        type="text"
                        value="{{ old('services.title', $sections['home.services_highlight']['title'] ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="text-sm font-semibold text-slate-700">Item {{ $i + 1 }}</h3>
                            <input
                                name="services[items][{{ $i }}][title]"
                                type="text"
                                placeholder="Title"
                                value="{{ old("services.items.$i.title", $sections['home.services_highlight']['items'][$i]['title'] ?? '') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                            <textarea
                                name="services[items][{{ $i }}][description]"
                                rows="3"
                                placeholder="Description"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >{{ old("services.items.$i.description", $sections['home.services_highlight']['items'][$i]['description'] ?? '') }}</textarea>
                            <input
                                name="services[icons][{{ $i }}]"
                                type="file"
                                accept="image/*"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                            @if (!empty($sections['home.services_highlight']['items'][$i]['icon']))
                                <p class="mt-2 text-xs text-slate-500">Current: {{ $sections['home.services_highlight']['items'][$i]['icon'] }}</p>
                            @endif
                        </div>
                    @endfor
                </div>
            </section>

            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">News Highlight</h2>
                <div>
                    <label class="text-sm font-semibold text-slate-700" for="news_title">{{ __('common.labels.title') }}</label>
                    <input
                        id="news_title"
                        name="news[title]"
                        type="text"
                        value="{{ old('news.title', $sections['home.news_highlight']['title'] ?? '') }}"
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                    >
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="text-sm font-semibold text-slate-700">Item {{ $i + 1 }}</h3>
                            <input
                                name="news[items][{{ $i }}][title]"
                                type="text"
                                placeholder="Title"
                                value="{{ old("news.items.$i.title", $sections['home.news_highlight']['items'][$i]['title'] ?? '') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                            <textarea
                                name="news[items][{{ $i }}][description]"
                                rows="3"
                                placeholder="Description"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >{{ old("news.items.$i.description", $sections['home.news_highlight']['items'][$i]['description'] ?? '') }}</textarea>
                            <input
                                name="news[items][{{ $i }}][url]"
                                type="text"
                                placeholder="URL"
                                value="{{ old("news.items.$i.url", $sections['home.news_highlight']['items'][$i]['url'] ?? '') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                        </div>
                    @endfor
                </div>
            </section>

            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Stats</h2>
                <div class="grid gap-4 md:grid-cols-4">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <input
                                name="stats[items][{{ $i }}][label]"
                                type="text"
                                placeholder="Label"
                                value="{{ old("stats.items.$i.label", $sections['home.stats']['items'][$i]['label'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                            <input
                                name="stats[items][{{ $i }}][value]"
                                type="text"
                                placeholder="Value"
                                value="{{ old("stats.items.$i.value", $sections['home.stats']['items'][$i]['value'] ?? '') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                        </div>
                    @endfor
                </div>
            </section>

            <section class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Footer Links</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <input
                                name="footer[items][{{ $i }}][label]"
                                type="text"
                                placeholder="Label"
                                value="{{ old("footer.items.$i.label", $sections['home.footer_links']['items'][$i]['label'] ?? '') }}"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                            <input
                                name="footer[items][{{ $i }}][url]"
                                type="text"
                                placeholder="URL"
                                value="{{ old("footer.items.$i.url", $sections['home.footer_links']['items'][$i]['url'] ?? '') }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            >
                        </div>
                    @endfor
                </div>
            </section>

            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    {{ __('common.actions.view') }}
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
@endsection

