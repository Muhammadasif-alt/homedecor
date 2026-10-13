@props(['project', 'aspect' => 'aspect-[4/3]', 'sizes' => '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw'])

<article {{ $attributes->class(['group']) }}>
    <a href="{{ route('projects.show', $project) }}" class="block">
        <div class="relative {{ $aspect }} overflow-hidden rounded-sm bg-sand-light">
            <x-picture :media="$project->cover" :sizes="$sizes" class="absolute inset-0"
                       imgClass="h-full w-full object-cover transition duration-[1.2s] ease-[var(--ease-soft)] group-hover:scale-105" />
            <span class="absolute right-4 top-4 grid size-11 place-items-center rounded-full bg-white/90 text-charcoal opacity-0 transition duration-300 group-hover:opacity-100">
                <x-icon name="arrow-up-right" size="18" />
            </span>
        </div>
        <div class="mt-5">
            @if ($project->category)
                <p class="eyebrow">{{ $project->category->name }}</p>
            @endif
            <h3 class="mt-2 font-serif text-2xl transition group-hover:text-walnut">{{ $project->title }}</h3>
        </div>
    </a>
</article>
