{{-- Simple text header for pages without a hero image (legal, blog listings, 404…). --}}
@props(['title', 'eyebrow' => null, 'breadcrumbs' => []])

<header class="border-b border-charcoal/10 bg-ivory-dark pb-16 pt-14 sm:pb-20 sm:pt-16">
    <div class="container-site">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-8" />
        @if ($eyebrow)<p class="eyebrow mb-4">{{ $eyebrow }}</p>@endif
        <h1 class="max-w-4xl text-5xl leading-[1.05] sm:text-6xl">{{ $title }}</h1>
        @if ($slot->isNotEmpty())
            <div class="mt-5 max-w-2xl text-lg text-charcoal-soft">{{ $slot }}</div>
        @endif
    </div>
</header>
