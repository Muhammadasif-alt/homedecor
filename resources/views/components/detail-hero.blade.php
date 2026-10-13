{{-- Image hero with H1 + breadcrumbs for detail templates (service, project, area). --}}
@props(['media' => null, 'title', 'eyebrow' => null, 'breadcrumbs' => []])

<section class="relative isolate flex min-h-[min(70svh,760px)] items-end overflow-hidden bg-charcoal text-white">
    @if ($media)
        <x-picture :media="$media" :priority="true" sizes="100vw" class="absolute inset-0 -z-10"
                   imgClass="h-full w-full object-cover animate-[heroZoom_14s_ease-out_forwards]" />
    @endif
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-charcoal/90 via-charcoal/45 to-charcoal/15"></div>

    <div class="container-site pb-16 pt-32 sm:pb-24">
        <div class="max-w-3xl">
            <x-breadcrumbs :items="$breadcrumbs" tone="light" class="mb-8" />
            @if ($eyebrow)<p class="eyebrow mb-5 text-sand!">{{ $eyebrow }}</p>@endif
            <h1 class="text-4xl leading-[1.04] text-white! sm:text-6xl">{{ $title }}</h1>
            @if ($slot->isNotEmpty())
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/85 sm:text-xl">{{ $slot }}</p>
            @endif
            @isset($actions)
                <div class="mt-10 flex flex-wrap gap-4">{{ $actions }}</div>
            @endisset
        </div>
    </div>
</section>
