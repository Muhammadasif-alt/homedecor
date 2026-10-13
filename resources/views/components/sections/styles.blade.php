@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'ivory')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>

    <div class="-mx-5 mt-14 flex snap-x snap-mandatory gap-6 overflow-x-auto px-5 pb-4 lg:mx-0 lg:grid lg:grid-cols-5 lg:overflow-visible lg:px-0">
        @foreach ($data['items'] as $item)
            @php $image = $data['media']->get($item['media_id'] ?? null); @endphp
            <article class="group w-64 shrink-0 snap-start lg:w-auto" data-reveal>
                <div class="relative aspect-[3/4] overflow-hidden rounded-sm bg-sand-light">
                    @if ($image)
                        <x-picture :media="$image" sizes="(min-width: 1024px) 20vw, 256px" class="absolute inset-0"
                                   imgClass="h-full w-full object-cover transition duration-[1.2s] group-hover:scale-105" />
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal/70 via-transparent to-transparent"></div>
                    <h3 class="absolute inset-x-0 bottom-0 p-5 font-serif text-2xl text-white!">{{ $item['title'] }}</h3>
                </div>
                @if (! empty($item['text']))
                    <p class="mt-4 text-sm leading-relaxed text-charcoal-soft">{{ $item['text'] }}</p>
                @endif
            </article>
        @endforeach
    </div>
</x-section>
