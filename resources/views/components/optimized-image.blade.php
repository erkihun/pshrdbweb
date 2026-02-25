@props([
    'src',
    'alt' => '',
    'priority' => false,
    'width' => null,
    'height' => null,
    'ratio' => null,
    'maxHeight' => null,
    'sizes' => null,
    'webp' => null,
    'class' => '',
])

@php
    $loading = $priority ? 'eager' : 'lazy';
    $stylePieces = [];

    if ($ratio) {
        $stylePieces[] = "aspect-ratio: {$ratio}";
    }

    if ($maxHeight) {
        $stylePieces[] = "max-height: {$maxHeight}";
    }

    $inlineStyle = implode('; ', $stylePieces);
    $existingStyle = trim($attributes->get('style', ''));
    $styleValue = trim(($inlineStyle ? "{$inlineStyle}; " : '') . $existingStyle);
    $classValue = trim("object-cover w-full h-full {$class} " . ($attributes->get('class', '')));
    $extraAttributes = $attributes->except(['class', 'style']);
@endphp

<picture class="block overflow-hidden">
    @if ($webp)
        <source srcset="{{ $webp }}" type="image/webp">
    @endif
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="{{ $loading }}"
        decoding="async"
        class="{{ $classValue }}"
        style="{{ $styleValue }}"
        @if ($sizes) sizes="{{ $sizes }}" @endif
        @if ($width) width="{{ $width }}" @endif
        @if ($height) height="{{ $height }}" @endif
        {{ $extraAttributes }}
    >
</picture>
