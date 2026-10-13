{{-- Keyboard-accessible comparison slider (range input drives the clip). --}}
@props(['before', 'after', 'label' => 'Transformation', 'sizes' => '(min-width: 1024px) 50vw, 100vw'])

<div x-data="beforeAfter()" {{ $attributes->class(['relative select-none overflow-hidden rounded-sm bg-sand-light', 'aspect-[3/2]' => ! str_contains($attributes->get('class', ''), 'aspect-')]) }}>
    <x-picture :media="$after" :sizes="$sizes" class="absolute inset-0" />
    <div class="absolute inset-0" :style="`clip-path: inset(0 ${100 - position}% 0 0)`">
        <x-picture :media="$before" :sizes="$sizes" class="absolute inset-0" />
    </div>
    <div class="pointer-events-none absolute inset-y-0 w-0.5 bg-white" :style="`left: ${position}%`">
        <span class="absolute top-1/2 left-1/2 grid size-12 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-charcoal shadow-lg">
            <x-icon name="arrow-left" size="14" /><x-icon name="arrow-right" size="14" />
        </span>
    </div>
    <span class="absolute left-4 top-4 rounded-full bg-charcoal/70 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-white">Before</span>
    <span class="absolute right-4 top-4 rounded-full bg-charcoal/70 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-white">After</span>
    <input type="range" min="0" max="100" x-model="position" aria-label="Compare before and after: {{ $label }}"
           class="absolute inset-0 h-full w-full cursor-ew-resize opacity-0">
</div>
