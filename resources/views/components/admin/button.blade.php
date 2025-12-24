@props([
    'variant' => 'primary',
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-2xl px-4 py-2 text-sm font-semibold focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition';
    $variantClasses = match ($variant) {
        'primary' => 'bg-primary text-white shadow-sm hover:bg-[#1D3A9A]',
        'secondary' => 'bg-white border border-brand-border text-brand-ink hover:bg-brand-bg',
        'ghost' => 'bg-transparent text-primary hover:bg-primary/10',
        default => 'bg-primary text-white',
    };
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
    {{ $slot }}
</button>
