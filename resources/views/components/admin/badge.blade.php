@props([
    'type' => 'info',
])

@php
    $variants = [
        'info' => 'bg-brand-blue/10 text-brand-blue',
        'warning' => 'bg-brand-gold/10 text-brand-gold',
        'success' => 'bg-brand-green/10 text-brand-green',
        'danger' => 'bg-red-50 text-red-600',
    ];
    $selectedVariant = $variants[$type] ?? $variants['info'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold $selectedVariant"]) }}>
    {{ $slot }}
</span>
