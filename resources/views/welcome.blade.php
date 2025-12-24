<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        @php
            $hero = $sections['home.hero'] ?? [];
            $services = $sections['home.services_highlight'] ?? [];
            $news = $sections['home.news_highlight'] ?? [];
            $stats = $sections['home.stats'] ?? [];
            $footerLinks = $sections['home.footer_links'] ?? [];
            $heroBg = !empty($hero['background_image']) ? asset('storage/' . $hero['background_image']) : null;
        @endphp
        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-20 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
                <div class="absolute -right-24 top-40 h-72 w-72 rounded-full bg-emerald-400/30 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-sky-400/20 blur-3xl"></div>
            </div>

            <main class="relative mx-auto flex min-h-screen max-w-6xl flex-col justify-center gap-16 px-6 py-16">
                <section class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-4 text-sm uppercase tracking-[0.2em] text-slate-400">
                            <span class="h-px w-10 bg-slate-700"></span>
                            {{ __('common.labels.homepage') }}
                        </div>
                        <div class="space-y-6">
                            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
                                {{ $hero['title'] ?? "Let's get started." }}
                            </h1>
                            <p class="max-w-2xl text-lg text-slate-300">
                                {{ $hero['subtitle'] ?? 'Laravel has an incredibly rich ecosystem. We suggest starting with the following.' }}
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4">
                            <a
                                href="{{ $hero['cta_url'] ?? '/dashboard' }}"
                                class="rounded-full bg-white px-6 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-200"
                            >
                                {{ $hero['cta_text'] ?? 'Visit Dashboard' }}
                            </a>
                            <a
                                href="{{ route('services.index') }}"
                                class="rounded-full border border-slate-700 px-6 py-2 text-sm font-semibold text-slate-200 transition hover:border-slate-500 hover:text-white"
                            >
                                {{ __('common.nav.services') }}
                            </a>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/60">
                        @if ($heroBg)
                            <img src="{{ $heroBg }}" alt="Hero background" class="h-full w-full object-cover opacity-90">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950"></div>
                        @endif
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold text-white">{{ $services['title'] ?? 'Top Services' }}</h2>
                        <a href="{{ route('services.index') }}" class="text-sm font-semibold text-slate-300 hover:text-white">
                            {{ __('common.actions.view') }}
                        </a>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach (($services['items'] ?? []) as $item)
                            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6">
                                @if (!empty($item['icon']))
                                    <img src="{{ asset('storage/' . $item['icon']) }}" alt="" class="h-10 w-10">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-slate-800"></div>
                                @endif
                                <h3 class="mt-4 text-lg font-semibold text-white">{{ $item['title'] ?? 'Service' }}</h3>
                                <p class="mt-2 text-sm text-slate-400">{{ $item['description'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold text-white">{{ $news['title'] ?? 'Latest News' }}</h2>
                        <a href="{{ route('news.index') }}" class="text-sm font-semibold text-slate-300 hover:text-white">
                            {{ __('common.actions.view') }}
                        </a>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach (($news['items'] ?? []) as $item)
                            <a
                                href="{{ $item['url'] ?? route('news.index') }}"
                                class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 transition hover:-translate-y-1"
                            >
                                <h3 class="text-lg font-semibold text-white">{{ $item['title'] ?? 'News' }}</h3>
                                <p class="mt-2 text-sm text-slate-400">{{ $item['description'] ?? '' }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="grid gap-4 md:grid-cols-4">
                    @foreach (($stats['items'] ?? []) as $stat)
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 text-center">
                            <div class="text-3xl font-semibold text-white">{{ $stat['value'] ?? '0' }}</div>
                            <div class="mt-2 text-sm text-slate-400">{{ $stat['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </section>

                <footer class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-800 pt-6 text-sm text-slate-400">
                    <span>{{ config('app.name') }} · {{ now()->year }}</span>
                    <div class="flex flex-wrap items-center gap-4">
                        @foreach (($footerLinks['items'] ?? []) as $link)
                            <a href="{{ $link['url'] ?? '#' }}" class="hover:text-white">
                                {{ $link['label'] ?? 'Link' }}
                            </a>
                        @endforeach
                    </div>
                </footer>
            </main>
        </div>
    </body>
</html>
