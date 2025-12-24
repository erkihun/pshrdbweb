<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $site_settings['site.branding']['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('meta')
    </head>
    <body class="font-sans antialiased bg-gray-50 text-slate-900">
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:inline-flex focus:h-10 focus:items-center focus:justify-center focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900 focus:shadow focus:outline-none focus:ring-2 focus:ring-brand-blue focus:ring-offset-2 focus:ring-offset-white"
        >
            Skip to content
        </a>
        @include('layouts.public-navbar')

        <main id="main-content" class="min-h-screen">
            @yield('content')
        </main>

        @include('layouts.public-footer')
        @php
            $officeHoursService = app(\App\Services\OfficeHoursService::class);
        @endphp
        @include('components.chat-widget', [
            'isOpen' => $officeHoursService->isOpen(),
            'summary' => $officeHoursService->summary(),
        ])
    </body>
</html>
