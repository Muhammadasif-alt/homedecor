{{--
    <x-button href="/contact" variant="primary|outline|light|ghost" size="md|lg" icon="arrow-right">Label</x-button>
    Renders <a> when href is given, otherwise <button>.
--}}
@props(['href' => null, 'variant' => 'primary', 'size' => 'md', 'icon' => null, 'type' => 'submit'])

@php
    $variants = [
        'primary' => 'bg-charcoal text-ivory hover:bg-walnut',
        'walnut' => 'bg-walnut text-white hover:bg-walnut-dark',
        'outline' => 'border border-charcoal/70 text-charcoal hover:bg-charcoal hover:text-ivory',
        'light' => 'border border-white/70 text-white hover:bg-white hover:text-charcoal',
        'ghost' => 'text-charcoal underline-offset-8 hover:underline px-0!',
    ];
    $sizes = ['sm' => 'px-4 py-2 text-xs', 'md' => 'px-6 py-3 text-sm', 'lg' => 'px-8 py-4 text-sm'];
    $classes = 'group inline-flex items-center justify-center gap-3 rounded-full font-semibold uppercase tracking-[0.14em] transition duration-300 '
        .($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <span>{{ $slot }}</span>
        @if ($icon)<x-icon :name="$icon" size="18" class="transition-transform duration-300 group-hover:translate-x-1" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        <span>{{ $slot }}</span>
        @if ($icon)<x-icon :name="$icon" size="18" class="transition-transform duration-300 group-hover:translate-x-1" />@endif
    </button>
@endif
