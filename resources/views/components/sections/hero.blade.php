@props(['section', 'data' => [], 'first' => false])

@php
    $full = $section->get('size', 'page') === 'full';
    $breadcrumbs = app(\App\Support\Seo::class)->breadcrumbs;
    $isPrimary = $first;
@endphp

<section @class([
    'relative isolate flex overflow-hidden bg-charcoal text-white',
    'min-h-[min(88svh,980px)] items-end' => $full,
    'min-h-[min(58svh,620px)] items-end sm:min-h-[min(62svh,680px)]' => ! $full,
])>
    @if ($section->media)
        <x-picture :media="$section->media" :priority="$isPrimary" sizes="100vw" class="absolute inset-0 -z-10"
                   imgClass="h-full w-full object-cover animate-[heroZoom_14s_ease-out_forwards]" />
    @endif
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-charcoal/90 via-charcoal/45 to-charcoal/15"></div>

    <div class="container-site pb-16 pt-32 sm:pb-24">
        <div class="max-w-3xl">
            @unless ($full)
                <x-breadcrumbs :items="$breadcrumbs" tone="light" class="mb-8" />
            @endunless
            @if ($section->eyebrow)
                <p class="eyebrow mb-5 text-sand!">{{ $section->eyebrow }}</p>
            @endif
            <{{ $isPrimary ? 'h1' : 'h2' }} @class([
                'text-white! leading-[1.02]',
                'text-5xl sm:text-6xl lg:text-7xl' => $full,
                'text-4xl sm:text-5xl lg:text-6xl' => ! $full,
            ])>{{ $section->heading }}</{{ $isPrimary ? 'h1' : 'h2' }}>
            @if ($section->body)
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/85 sm:text-xl">{{ strip_tags($section->body) }}</p>
            @endif
            @if ($buttons = $section->get('buttons'))
                <div class="mt-10 flex flex-wrap gap-4">
                    @foreach ($buttons as $button)
                        <x-button :href="url($button['url'])" :variant="$loop->first ? 'walnut' : 'light'" size="lg" :icon="$loop->first ? 'arrow-right' : null">
                            {{ $button['label'] }}
                        </x-button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
