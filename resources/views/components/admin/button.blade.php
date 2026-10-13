@props(['href' => null, 'variant' => 'primary', 'icon' => null, 'type' => 'submit', 'size' => 'md'])

@php
    $variants = [
        'primary' => 'border-transparent bg-charcoal text-ivory hover:bg-walnut-dark',
        'secondary' => 'border-charcoal/15 bg-white text-charcoal hover:border-walnut hover:text-walnut',
        'accent' => 'border-transparent bg-sand text-charcoal hover:bg-sand-light',
        'danger' => 'border-red-200 bg-white text-red-700 hover:bg-red-50',
        'ghost' => 'border-transparent bg-transparent text-charcoal-soft hover:bg-ivory-dark hover:text-charcoal',
    ];
    $sizes = ['sm' => 'px-2.5 py-1.5 text-xs', 'md' => 'px-4 py-2 text-sm'];
    $classes = 'inline-flex items-center justify-center gap-1.5 rounded border font-semibold transition-colors '
        .'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-walnut disabled:cursor-not-allowed disabled:opacity-50 '
        .$sizes[$size].' '.$variants[$variant];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-admin.icon :name="$icon" class="size-4" />@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)<x-admin.icon :name="$icon" class="size-4" />@endif
        {{ $slot }}
    </button>
@endif
