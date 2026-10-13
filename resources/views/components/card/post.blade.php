@props(['post', 'headingLevel' => 3])

@php $tag = 'h'.$headingLevel; @endphp

<article {{ $attributes->class(['group flex flex-col']) }} data-reveal>
    <a href="{{ route('blog.show', $post) }}" class="relative block aspect-[3/2] overflow-hidden rounded-sm bg-sand-light">
        <x-picture :media="$post->featuredMedia" sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw" class="absolute inset-0"
                   imgClass="h-full w-full object-cover transition duration-[1.2s] ease-[var(--ease-soft)] group-hover:scale-105" />
    </a>
    <div class="mt-6 flex flex-1 flex-col">
        <p class="flex flex-wrap items-center gap-x-3 text-xs font-semibold uppercase tracking-[0.16em] text-walnut">
            @if ($category = $post->categories->first())
                <span>{{ $category->name }}</span><span aria-hidden="true" class="text-sand">•</span>
            @endif
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j M Y') }}</time>
        </p>
        <{{ $tag }} class="mt-3 font-serif text-2xl leading-snug">
            <a href="{{ route('blog.show', $post) }}" class="transition group-hover:text-walnut">{{ $post->title }}</a>
        </{{ $tag }}>
        <p class="mt-3 line-clamp-3 text-charcoal-soft">{{ $post->excerpt }}</p>
        <a href="{{ route('blog.show', $post) }}" class="mt-auto inline-flex items-center gap-2 pt-5 text-xs font-semibold uppercase tracking-[0.16em] text-charcoal" aria-label="Read {{ $post->title }}">
            Read article <x-icon name="arrow-right" size="16" class="transition-transform group-hover:translate-x-1" />
        </a>
    </div>
</article>
