{{-- Rendered only when genuine before + after photograph pairs exist (section items or projects). --}}
@props(['section', 'data' => [], 'first' => false])

@php $count = count($data['pairs']); @endphp

<x-section :tone="$section->get('tone', 'white')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>

    <div @class(['mt-14 grid gap-8', 'md:grid-cols-2 lg:grid-cols-3' => $count >= 3, 'lg:grid-cols-2' => $count === 2, 'mx-auto max-w-5xl' => $count === 1])>
        @foreach ($data['pairs'] as $pair)
            <figure data-reveal>
                <x-before-after :before="$pair['before']" :after="$pair['after']" :label="$pair['title']" />
                @if ($pair['title'])
                    <figcaption class="mt-4">
                        @if ($pair['url'])
                            <a href="{{ $pair['url'] }}" class="inline-flex items-center gap-2 font-serif text-xl hover:text-walnut">{{ $pair['title'] }} <x-icon name="arrow-right" size="18" /></a>
                        @else
                            <span class="font-serif text-xl">{{ $pair['title'] }}</span>
                        @endif
                    </figcaption>
                @endif
            </figure>
        @endforeach
    </div>

    <p class="mt-8 text-center text-sm text-charcoal/60">Drag the slider on each photo to compare before and after.</p>
</x-section>
