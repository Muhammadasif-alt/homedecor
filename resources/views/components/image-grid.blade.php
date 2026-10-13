{{--
    Balanced image grid with an accessible lightbox (Esc / ← / → supported).
    <x-image-grid :images="$mediaCollection" :captions="['id' => 'caption']" />
    layout="masonry" (default) keeps natural proportions; layout="feature" shows one large lead image
    followed by equal 4:3 tiles in pairs (a leftover tile spans the full width), so nothing looks uneven.
    :limit="3" shows only the first tiles (keeps the grid level with a text column); the rest stay in the lightbox.
--}}
@props(['images', 'captions' => [], 'layout' => 'masonry', 'limit' => null])

@php
    $images = $images->values();
    $items = $images->map(fn ($m) => [
        'src' => $m->url(1600),
        'alt' => $m->alt,
        'caption' => $captions[$m->id] ?? $m->caption,
        'width' => $m->width,
        'height' => $m->height,
    ]);
    $total = $images->count();
    $shown = $limit ? min($limit, $total) : $total;
    $hidden = $total - $shown;
@endphp

<div {{ $attributes }} x-data="lightbox(@js($items))" @keydown.window="onKey($event)">
    @if ($layout === 'feature')
        <ul class="grid grid-cols-2 gap-4">
            @foreach ($images->take($shown) as $image)
                @php
                    $lead = $loop->first;
                    $wide = $lead || ($loop->last && ($shown - 1) % 2 === 1);
                @endphp
                <li @class(['col-span-2' => $wide]) data-reveal>
                    <button type="button" @click="open({{ $loop->index }})"
                            @class(['group relative block w-full overflow-hidden rounded-md bg-ivory-dark shadow-[0_20px_40px_-24px_rgba(41,43,39,0.45)]', 'aspect-[16/10]' => $wide, 'aspect-[4/3]' => ! $wide])
                            aria-label="Enlarge image: {{ $image->alt }}">
                        <x-picture :media="$image" :sizes="$wide ? '(min-width: 1024px) 50vw, 100vw' : '(min-width: 1024px) 25vw, 50vw'" class="absolute inset-0"
                                   imgClass="h-full w-full object-cover transition duration-[1.2s] ease-out group-hover:scale-105" />
                        <span class="absolute inset-0 bg-gradient-to-t from-charcoal/45 via-transparent to-transparent opacity-0 transition duration-500 group-hover:opacity-100"></span>
                        <span class="absolute bottom-4 right-4 grid size-11 translate-y-2 place-items-center rounded-full bg-white/95 text-charcoal opacity-0 shadow-lg transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                            <x-icon name="plus" size="18" />
                        </span>
                        @if ($lead && $total > 1)
                            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-charcoal backdrop-blur">
                                {{ $total }} photos
                            </span>
                        @endif
                        @if ($loop->last && $hidden > 0)
                            <span class="absolute inset-0 grid place-items-center bg-charcoal/45 font-serif text-3xl text-white">+{{ $hidden }} more</span>
                        @endif
                    </button>
                </li>
            @endforeach
        </ul>
    @else
        <ul class="columns-1 gap-4 sm:columns-2 lg:columns-3 [&>li]:mb-4">
            @foreach ($images as $image)
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
    @endif

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
