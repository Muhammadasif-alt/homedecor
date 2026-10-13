{{-- Grid of {title, text} items with an icon bullet — used for "What's included" lists. --}}
@props(['items', 'icon' => 'check'])

<ul {{ $attributes->class(['grid gap-6 sm:grid-cols-2 lg:grid-cols-3']) }}>
    @foreach ($items as $item)
        <li class="rounded-sm bg-white p-8" data-reveal>
            <span class="grid size-10 place-items-center rounded-full bg-walnut text-white"><x-icon :name="$icon" size="18" stroke="2" /></span>
            <h3 class="mt-5 font-serif text-2xl">{{ $item['title'] }}</h3>
            @if (! empty($item['text']))
                <p class="mt-2 leading-relaxed text-charcoal-soft">{{ $item['text'] }}</p>
            @endif
        </li>
    @endforeach
</ul>
