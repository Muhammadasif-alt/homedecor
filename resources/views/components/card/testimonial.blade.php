@props(['testimonial'])

<figure {{ $attributes->class(['flex h-full flex-col rounded-sm bg-white p-8 sm:p-10']) }} data-reveal>
    <x-icon name="quote" size="36" class="text-sand" />
    @if ($testimonial->rating)
        <div class="mt-5 flex gap-1 text-walnut" role="img" aria-label="Rated {{ $testimonial->rating }} out of 5">
            @for ($i = 1; $i <= 5; $i++)
                <x-icon name="star" size="16" @class(['fill-current' => $i <= $testimonial->rating, 'opacity-30' => $i > $testimonial->rating]) />
            @endfor
        </div>
    @endif
    <blockquote class="mt-5 flex-1 font-serif text-xl leading-relaxed text-charcoal">“{{ $testimonial->content }}”</blockquote>
    <figcaption class="mt-8 flex items-center gap-4">
        @if ($testimonial->photo)
            <x-picture :media="$testimonial->photo" sizes="56px" class="block size-14 overflow-hidden rounded-full" />
        @endif
        <span>
            <span class="block font-semibold">{{ $testimonial->customer_name }}</span>
            @if ($testimonial->service)
                <span class="text-sm text-charcoal-soft">{{ $testimonial->service->name }}</span>
            @endif
        </span>
    </figcaption>
</figure>
