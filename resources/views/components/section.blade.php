{{--
    Vertical rhythm + background wrapper for every page section.
    <x-section tone="ivory|white|sand|charcoal" id="services">…</x-section>
--}}
@props(['tone' => 'ivory', 'spacing' => 'normal'])

@php
    $tones = [
        'ivory' => 'bg-ivory',
        'white' => 'bg-white',
        'sand' => 'bg-ivory-dark',
        'charcoal' => 'bg-charcoal text-ivory',
    ];
    $spacings = ['tight' => 'py-14 sm:py-16', 'normal' => 'py-20 sm:py-28', 'loose' => 'py-24 sm:py-36'];
@endphp

<section {{ $attributes->class([$tones[$tone] ?? $tones['ivory'], $spacings[$spacing] ?? $spacings['normal']]) }}>
    <div class="container-site">
        {{ $slot }}
    </div>
</section>
