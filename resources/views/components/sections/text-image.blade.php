@props(['section', 'data' => [], 'first' => false])

@php
    $imageLeft = $section->get('image_position', 'right') === 'left';
    $button = $section->get('button');
@endphp

<x-section :tone="$section->get('tone', 'ivory')">
    <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
        @if ($section->media)
            <div @class(['relative', 'lg:order-last' => ! $imageLeft]) data-reveal>
                <div class="relative aspect-[4/5] overflow-hidden rounded-sm sm:aspect-[5/4] lg:aspect-[4/5]">
                    <x-picture :media="$section->media" sizes="(min-width: 1024px) 45vw, 100vw" class="absolute inset-0" />
                </div>
                <div @class(['absolute -bottom-6 hidden h-40 w-40 border border-sand lg:block', '-right-6' => $imageLeft, '-left-6' => ! $imageLeft]) aria-hidden="true"></div>
            </div>
        @endif

        <div @class(['lg:col-span-2 max-w-3xl' => ! $section->media])>
            <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left" />
            <x-rich-text :html="$section->body" class="mt-6" data-reveal />

            @if ($bullets = $section->get('bullets'))
                <ul class="mt-8 grid gap-3 sm:grid-cols-2" data-reveal>
                    @foreach ($bullets as $bullet)
                        <li class="flex gap-3 text-charcoal">
                            <span class="mt-0.5 grid size-6 shrink-0 place-items-center rounded-full bg-walnut text-white"><x-icon name="check" size="14" stroke="2" /></span>
                            {{ $bullet }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($button)
                <div class="mt-10" data-reveal>
                    <x-button :href="url($button['url'])" icon="arrow-right">{{ $button['label'] }}</x-button>
                </div>
            @endif
        </div>
    </div>
</x-section>
