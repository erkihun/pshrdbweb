<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $siteSettings = $site_settings ?? [];
            $branding = $siteSettings['site.branding'] ?? [];
            $siteName = $branding['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel');
        @endphp
        <title>{{ $siteName }}</title>
        @include('partials.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php
            $logoPath = $branding['logo_path'] ?? null;
            $bannerPath = $branding['login_banner_path'] ?? $branding['banner_path'] ?? null;
            $logoUrl = $logoPath ? asset('storage/' . ltrim($logoPath, '/')) : null;
            $bannerUrl = $bannerPath ? asset('storage/' . ltrim($bannerPath, '/')) : null;
        @endphp

        <div class="relative min-h-screen overflow-hidden bg-slate-950">
            @if($bannerUrl)
                <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ $bannerUrl }}')"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950/95 via-slate-900/90 to-blue-950/85"></div>

            <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10">
                <div class="grid w-full max-w-6xl overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl backdrop-blur md:grid-cols-2">
                    <div class="hidden flex-col justify-between bg-gradient-to-br from-slate-900/70 via-slate-800/65 to-blue-900/60 p-10 md:flex">
                        <div class="space-y-6 text-center">
                            <div class="flex justify-center">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $siteName }} logo" class="h-20 w-auto object-contain">
                                @else
                                    <x-application-logo class="h-20 w-20 fill-current text-white/80" />
                                @endif
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.28em] text-blue-200/80">Admin Portal</p>
                                <h1 class="mt-3 text-3xl font-semibold leading-tight text-white">{{ $siteName }}</h1>
                                <p class="mx-auto mt-3 max-w-md text-sm text-slate-200/80">Secure access for administrators and officers.</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-300/70">&copy; {{ date('Y') }} {{ $siteName }}</p>
                    </div>

                    <div class="bg-white px-6 py-8 sm:px-10 sm:py-12">
                        <div class="mb-6 text-center md:hidden">
                            <div class="mb-3 flex justify-center">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $siteName }} logo" class="h-12 w-auto object-contain">
                                @else
                                    <x-application-logo class="h-10 w-10 fill-current text-slate-600" />
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $siteName }}</p>
                                <p class="text-xs uppercase tracking-wide text-slate-500">Admin Portal</p>
                            </div>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
