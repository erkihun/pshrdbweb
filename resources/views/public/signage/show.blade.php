<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $template->name_en ?: $display->title_en ?: 'Signage' }}</title>
        @include('partials.favicon')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .ticker-mask {
                animation: ticker 18s linear infinite;
            }

            @keyframes ticker {
                0% {
                    transform: translateX(100%);
                }
                100% {
                    transform: translateX(-100%);
                }
            }
        </style>
    </head>
    <body class="scrollbar-hidden bg-slate-950 text-white">
        <div class="flex min-h-screen items-stretch justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-4 py-6">
            <section class="relative flex w-full max-w-[540px] flex-col gap-6 rounded-[40px] border border-white/10 bg-gradient-to-b from-slate-900/90 to-slate-950/90 p-6 shadow-2xl shadow-black/40">
                <div class="flex items-center justify-between gap-3 text-xs uppercase tracking-[0.35em] text-slate-400">
                    <span>{{ $template->name_en ?: 'Signage' }}</span>
                    <span>{{ $display->refresh_seconds }}s refresh</span>
                </div>

                @php
                    $payload = $display->payload ?? [];
                    $header = $payload['header'] ?? [];
                    $left = $payload['left'] ?? [];
                    $right = $payload['right'] ?? [];
                    $body = $payload['body'] ?? [];
                    $footer = $payload['footer'] ?? [];

                    $leftItems = collect($left['items'] ?? [])->filter()->values();
                    $bodyItems = collect($body['items'] ?? [])->filter()->values();
                    $rightStats = collect($right['stats'] ?? [])->filter()->values();

                    $headerTitle = $header['title'] ?? $display->title_en ?? $display->title_am ?? '';
                    $headerSubtitle = $header['subtitle'] ?? $header['subtitle'] ?? '';
                    $footerTicker = $footer['ticker'] ?? null;
                @endphp

                @switch($template->layout)
                    @case('header_two_cols_footer')
                        <div class="flex flex-col gap-4">
                            <header class="space-y-2 rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-2xl font-semibold text-white">{{ $headerTitle }}</p>
                                @if($headerSubtitle)
                                    <p class="text-sm text-slate-300">{{ $headerSubtitle }}</p>
                                @endif
                            </header>

                            <div class="grid flex-1 grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="space-y-3 rounded-3xl border border-white/5 bg-white/5 p-4">
                                    <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Left</p>
                                    @forelse($leftItems as $item)
                                        <div class="rounded-2xl bg-white/5 p-3 text-sm">
                                            @if(is_array($item))
                                                <p class="font-semibold">{{ $item['title'] ?? $item['label'] ?? '' }}</p>
                                                <p class="text-xs text-slate-300">{{ $item['text'] ?? $item['value'] ?? '' }}</p>
                                            @else
                                                <p>{{ $item }}</p>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-500">Add items via payload</p>
                                    @endforelse
                                </div>
                                <div class="space-y-3 rounded-3xl border border-white/5 bg-white/5 p-4">
                                    <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Right</p>
                                    @forelse($rightStats as $stat)
                                        <div class="rounded-2xl bg-white/5 p-3">
                                            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">{{ $stat['label'] ?? '' }}</p>
                                            <p class="text-2xl font-semibold text-white">{{ $stat['value'] ?? '' }}</p>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-500">Add stats via payload</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @break

                    @case('header_body_footer')
                        <div class="flex h-full flex-col gap-4">
                            <header class="space-y-2 rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-3xl font-semibold text-white">{{ $headerTitle }}</p>
                                @if($headerSubtitle)
                                    <p class="text-sm text-slate-300">{{ $headerSubtitle }}</p>
                                @endif
                            </header>

                            <section class="flex-1 space-y-3 overflow-y-auto rounded-3xl border border-white/5 bg-white/5 p-4">
                                @forelse($bodyItems as $item)
                                    <article class="rounded-2xl bg-white/10 p-4">
                                        @if(is_array($item))
                                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ $item['label'] ?? '' }}</p>
                                            <p class="text-3xl font-semibold text-white">{{ $item['value'] ?? $item['title'] ?? '' }}</p>
                                            <p class="text-xs text-slate-300">{{ $item['subtitle'] ?? $item['description'] ?? '' }}</p>
                                        @else
                                            <p class="text-lg text-white">{{ $item }}</p>
                                        @endif
                                    </article>
                                @empty
                                    <p class="text-xs text-slate-500">Add body content via payload</p>
                                @endforelse
                            </section>
                        </div>
                        @break

                    @case('split_three_rows')
                        <div class="flex h-full flex-col gap-4">
                            <section class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-2xl font-semibold text-white">{{ $headerTitle }}</p>
                                @if($headerSubtitle)
                                    <p class="text-xs text-slate-300">{{ $headerSubtitle }}</p>
                                @endif
                            </section>

                            <section class="flex flex-1 flex-col gap-3 overflow-hidden rounded-3xl border border-white/5 bg-white/5 p-4">
                                <div class="grid flex-1 grid-cols-1 gap-3 lg:grid-cols-2">
                                    <div class="space-y-3">
                                        @forelse($leftItems as $item)
                                            <div class="rounded-2xl bg-white/5 p-3 text-sm">
                                                {{ is_array($item) ? ($item['title'] ?? $item['label'] ?? '') : $item }}
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500">Provide left content</p>
                                        @endforelse
                                    </div>
                                    <div class="space-y-3">
                                        @forelse($rightStats as $stat)
                                            <div class="rounded-2xl bg-white/5 p-3">
                                                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">{{ $stat['label'] ?? '' }}</p>
                                                <p class="text-2xl font-semibold text-white">{{ $stat['value'] ?? '' }}</p>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500">Provide right stats</p>
                                        @endforelse
                                    </div>
                                </div>
                            </section>
                        </div>
                        @break

                    @default
                        <div class="flex h-full flex-col gap-4">
                            <section class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                <p class="text-2xl font-semibold text-white">{{ $headerTitle }}</p>
                            </section>
                            <section class="flex flex-1 flex-col gap-3 rounded-3xl border border-white/5 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Details</p>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    @foreach($leftItems as $item)
                                        <div class="rounded-2xl bg-white/5 p-3 text-sm">
                                            {{ is_array($item) ? ($item['title'] ?? $item['label'] ?? '') : $item }}
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                @endswitch

                @if($footerTicker)
                    <div class="mt-auto rounded-3xl border border-white/5 bg-white/5 p-3">
                        <div class="relative overflow-hidden">
                            <div class="ticker-mask whitespace-nowrap text-sm font-semibold text-white">
                                {{ $footerTicker }}
                            </div>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        <script>
            (function () {
                const refreshSeconds = {{ $display->refresh_seconds }};
                if (refreshSeconds > 0) {
                    setTimeout(() => window.location.reload(), refreshSeconds * 1000);
                }
            })();
        </script>
    </body>
</html>
