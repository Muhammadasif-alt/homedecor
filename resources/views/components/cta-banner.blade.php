{{-- Full-width image call-to-action used by the CMS `cta` section and the detail templates. --}}
@props(['media' => null, 'eyebrow' => null, 'heading', 'body' => null, 'buttons' => []])

<section class="relative isolate overflow-hidden bg-charcoal py-28 text-center text-white sm:py-36">
    @if ($media)
        <x-picture :media="$media" sizes="100vw" class="absolute inset-0 -z-10" />
    @endif
    <div class="absolute inset-0 -z-10 bg-charcoal/70"></div>

    <div class="container-site max-w-3xl" data-reveal>
        @if ($eyebrow)<p class="eyebrow mb-5 text-sand!">{{ $eyebrow }}</p>@endif
        <h2 class="text-4xl leading-tight text-white! sm:text-6xl">{{ $heading }}</h2>
        @if ($body)<p class="mx-auto mt-6 max-w-2xl text-lg text-white/80">{{ $body }}</p>@endif
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            @foreach ($buttons as $button)
                <x-button :href="url($button['url'])" :variant="$loop->first ? 'walnut' : 'light'" size="lg" :icon="$loop->first ? 'arrow-right' : null">{{ $button['label'] }}</x-button>
            @endforeach
        </div>
    </div>
</section>
