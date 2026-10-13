{{--
    Balanced image grid with an accessible lightbox (Esc / ← / → supported).
    <x-image-grid :images="$mediaCollection" :captions="['id' => 'caption']" />
--}}
@props(['images', 'captions' => []])

@php
    $items = $images->values()->map(fn ($m) => [
        'src' => $m->url(1600),
        'alt' => $m->alt,
        'caption' => $captions[$m->id] ?? $m->caption,
        'width' => $m->width,
        'height' => $m->height,
    ]);
@endphp

<div {{ $attributes }} x-data="lightbox(@js($items))" @keydown.window="onKey($event)">
    <ul class="columns-1 gap-4 sm:columns-2 lg:columns-3 [&>li]:mb-4">
        @foreach ($images->values() as $image)
            <li class="break-inside-avoid" data-reveal>
                <button type="button" @click="open({{ $loop->index }})" class="group relative block w-full overflow-hidden rounded-sm" aria-label="Enlarge image: {{ $image->alt }}">
                    <x-picture :media="$image" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" class="block"
                               imgClass="w-full transition duration-[1.2s] group-hover:scale-105" />
                    <span class="absolute inset-0 grid place-items-center bg-charcoal/0 opacity-0 transition group-hover:bg-charcoal/30 group-hover:opacity-100">
                        <span class="grid size-12 place-items-center rounded-full bg-white text-charcoal"><x-icon name="plus" size="20" /></span>
                    </span>
                </button>
            </li>
        @endforeach
    </ul>

    <template x-teleport="body">
        <div x-show="current" x-cloak x-transition.opacity x-trap.noscroll="current !== null"
             class="fixed inset-0 z-[90] flex items-center justify-center bg-charcoal/95 p-4 sm:p-10" role="dialog" aria-modal="true" aria-label="Image viewer">
            <button type="button" @click="close()" class="absolute right-4 top-4 grid size-12 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" aria-label="Close">
                <x-icon name="x" size="22" />
            </button>
            <button type="button" @click="prev()" class="absolute left-2 grid size-12 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 sm:left-6" aria-label="Previous image">
                <x-icon name="arrow-left" size="22" />
            </button>
            <figure class="max-h-full max-w-6xl">
                <img :src="current?.src" :alt="current?.alt" :width="current?.width" :height="current?.height" class="max-h-[80vh] w-auto rounded-sm object-contain">
                <figcaption x-show="current?.caption" x-text="current?.caption" class="mt-4 text-center text-sm text-white/80"></figcaption>
            </figure>
            <button type="button" @click="next()" class="absolute right-2 grid size-12 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 sm:right-6" aria-label="Next image">
                <x-icon name="arrow-right" size="22" />
            </button>
        </div>
    </template>
</div>
