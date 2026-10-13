@props(['service', 'wide' => false, 'sizes' => '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw'])

<article {{ $attributes->class(['group relative']) }} data-reveal>
    <a href="{{ route('services.show', $service) }}" class="block">
        <div @class(['relative overflow-hidden rounded-sm bg-sand-light', 'aspect-[4/5] sm:aspect-[21/9]' => $wide, 'aspect-[4/5]' => ! $wide])>
            <x-picture :media="$service->heroMedia" :sizes="$sizes" class="absolute inset-0"
                       imgClass="h-full w-full object-cover transition duration-[1.2s] ease-[var(--ease-soft)] group-hover:scale-105" />
            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/80 via-charcoal/10 to-transparent"></div>
            <span class="absolute left-5 top-5 grid size-11 place-items-center rounded-full bg-ivory/90 text-walnut backdrop-blur">
                <x-icon :name="$service->icon ?? 'sparkles'" size="20" />
            </span>
            <div class="absolute inset-x-0 bottom-0 p-6 text-white sm:p-7">
                <h3 class="font-serif text-2xl text-white! sm:text-[1.7rem]">{{ $service->name }}</h3>
                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-white/80">{{ $service->short_description }}</p>
                <span class="mt-5 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-sand">
                    Discover <x-icon name="arrow-right" size="16" class="transition-transform group-hover:translate-x-1" />
                </span>
            </div>
        </div>
    </a>
</article>
