{{--
    Eyebrow + heading + intro used at the top of most sections.
    <x-section-heading eyebrow="Our services" heading="…" align="center|left" :level="2">Intro text</x-section-heading>
--}}
@props(['eyebrow' => null, 'heading' => null, 'align' => 'center', 'level' => 2, 'tone' => 'dark'])

@php
    $tag = 'h'.$level;
    $center = $align === 'center';
@endphp

@if ($eyebrow || $heading || $slot->isNotEmpty())
    <div {{ $attributes->class(['max-w-3xl', 'mx-auto text-center' => $center]) }} data-reveal>
        @if ($eyebrow)
            <p @class(['eyebrow mb-4', 'text-sand!' => $tone === 'light'])>{{ $eyebrow }}</p>
        @endif
        @if ($heading)
            <{{ $tag }} @class([
                'text-4xl leading-[1.08] sm:text-5xl',
                'text-white!' => $tone === 'light',
            ])>{{ $heading }}</{{ $tag }}>
        @endif
        @if ($slot->isNotEmpty())
            <div @class([
                'mt-5 text-lg leading-relaxed',
                'text-charcoal-soft' => $tone === 'dark',
                'text-white/80' => $tone === 'light',
            ])>{{ $slot }}</div>
        @endif
    </div>
@endif
