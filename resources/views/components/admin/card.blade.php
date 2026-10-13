@props(['title' => null, 'description' => null, 'padding' => true])

<section {{ $attributes->merge(['class' => 'rounded-lg border border-charcoal/10 bg-white shadow-sm']) }}>
    @if ($title || isset($actions))
        <header class="flex flex-wrap items-start justify-between gap-3 border-b border-charcoal/10 px-5 py-4 sm:px-6">
            <div>
                @if ($title)<h2 class="font-sans text-base font-semibold text-charcoal">{{ $title }}</h2>@endif
                @if ($description)<p class="mt-0.5 text-sm text-charcoal-soft">{{ $description }}</p>@endif
            </div>
            {{ $actions ?? '' }}
        </header>
    @endif
    <div @class(['px-5 py-5 sm:px-6' => $padding])>
        {{ $slot }}
    </div>
    @isset($footer)
        <footer class="flex flex-wrap items-center justify-end gap-3 rounded-b-lg border-t border-charcoal/10 bg-ivory/50 px-5 py-3 sm:px-6">
            {{ $footer }}
        </footer>
    @endisset
</section>
