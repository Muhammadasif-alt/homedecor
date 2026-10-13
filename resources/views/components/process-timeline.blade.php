{{-- Vertical alternating timeline used on the Process page and the service template. --}}
@props(['steps', 'media' => collect()])

<ol {{ $attributes->class(['relative space-y-16 before:absolute before:left-6 before:top-0 before:h-full before:w-px before:bg-sand md:before:left-1/2']) }}>
    @foreach ($steps as $step)
        @php $image = isset($step['media_id']) ? $media->get($step['media_id']) : null; @endphp
        <li class="relative grid gap-8 pl-16 md:grid-cols-2 md:gap-20 md:pl-0" data-reveal>
            <span class="absolute left-0 top-0 grid size-12 place-items-center rounded-full bg-walnut text-white ring-8 ring-ivory md:left-1/2 md:-translate-x-1/2">
                <x-icon :name="$step['icon'] ?? 'sparkles'" size="20" />
            </span>
            <div @class(['md:text-right md:pr-4' => $loop->odd, 'md:col-start-2 md:pl-4' => $loop->even])>
                <p class="eyebrow">Step {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                <h3 class="mt-3 font-serif text-3xl">{{ $step['title'] }}</h3>
                <p class="mt-4 leading-relaxed text-charcoal-soft">{{ $step['text'] }}</p>
            </div>
            @if ($image)
                <div @class(['aspect-[3/2] overflow-hidden rounded-sm', 'md:row-start-1 md:col-start-1' => $loop->even])>
                    <x-picture :media="$image" sizes="(min-width: 768px) 40vw, 100vw" class="block h-full" />
                </div>
            @endif
        </li>
    @endforeach
</ol>
