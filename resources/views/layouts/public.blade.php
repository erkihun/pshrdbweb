<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $seoMeta = $seoMeta ?? [];
            $branding = $site_settings['site.branding'] ?? [];
            $seoSettings = $site_settings['site.seo'] ?? [];
            $defaultTitle = $branding['site_name_' . app()->getLocale()] ?? config('app.name', 'Laravel');
            $defaultDescription = $seoSettings['description_' . app()->getLocale()] ?? '';
            $googleVerification = $seoSettings['google_verification'] ?? env('GOOGLE_SITE_VERIFICATION');
            $bingVerification = $seoSettings['bing_verification'] ?? env('BING_WEBMASTER_VERIFICATION');
        @endphp
        <x-seo.meta
            :title="$seoMeta['title'] ?? $defaultTitle"
            :description="$seoMeta['description'] ?? $defaultDescription"
            :image="$seoMeta['image'] ?? null"
            :url="$seoMeta['url'] ?? url()->current()"
            :canonical="$seoMeta['canonical'] ?? null"
            :type="$seoMeta['type'] ?? 'website'"
            :locale="$seoMeta['locale'] ?? app()->getLocale()"
            :robots="$seoMeta['robots'] ?? 'index, follow'"
        />
        @if($googleVerification)
            <meta name="google-site-verification" content="{{ $googleVerification }}">
        @endif
        @if($bingVerification)
            <meta name="msvalidate.01" content="{{ $bingVerification }}">
        @endif
        @include('partials.favicon')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Nyala&family=Abyssinica+SIL:wght@400;600&family=Noto+Sans+Ethiopic:wght@400;600&family=Noto+Serif+Ethiopic:wght@400;600&display=swap"
            rel="stylesheet"
        >


        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
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
            <div class="w-full px-4 sm:px-6 lg:px-8">
                @php
                    $breadcrumbItems = $breadcrumbItems ?? null;
                @endphp
                <x-breadcrumbs :items="$breadcrumbItems" variant="full" class="mb-6" />
            </div>
        
                @yield('content')
            
        </main>


        @include('layouts.public-footer')
        @php
            $officeHoursService = app(\App\Services\OfficeHoursService::class);
        @endphp
        @stack('scripts')
 
    </body>
</html>
