@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
    'locale' => null,
    'canonical' => null,
    'robots' => 'index, follow',
])

@php
    $locale = $locale ?: app()->getLocale();
    $siteName = config('app.name', 'Laravel');
    $titleTag = $title ?: $siteName;
    $descriptionTag = trim($description ?? '');
    $canonicalUrl = $canonical ?? $url ?? url()->current();
    $hreflangUrl = $canonicalUrl;
    $alternateLangs = ['am', 'en'];
    $twitterCard = filled($image) ? 'summary_large_image' : 'summary';
@endphp

<title>{{ $titleTag }}</title>

@if ($descriptionTag)
    <meta name="description" content="{{ $descriptionTag }}">
@endif

<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $titleTag }}">
@if ($descriptionTag)
    <meta property="og:description" content="{{ $descriptionTag }}">
@endif
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">
@if ($image)
    <meta property="og:image" content="{{ $image }}">
@endif

<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:title" content="{{ $titleTag }}">
@if ($descriptionTag)
    <meta name="twitter:description" content="{{ $descriptionTag }}">
@endif
@if ($image)
    <meta name="twitter:image" content="{{ $image }}">
@endif

@if ($robots)
    <meta name="robots" content="{{ $robots }}">
@endif

@foreach ($alternateLangs as $lang)
    <link rel="alternate" hreflang="{{ $lang }}" href="{{ $hreflangUrl }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ $hreflangUrl }}">
