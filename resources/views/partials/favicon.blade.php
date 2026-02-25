@php
    $settings = $site_settings ?? [];
    $branding = $settings['site.branding'] ?? [];
    $faviconPath = $branding['favicon_path'] ?? null;
    $faviconUrl = $faviconPath
        ? asset('storage/' . ltrim($faviconPath, '/'))
        : asset('favicon.ico');
@endphp
<link rel="icon" href="{{ $faviconUrl }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ $faviconUrl }}" type="image/x-icon">
