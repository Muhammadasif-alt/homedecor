@props(['site', 'items', 'services'])

@php
    $links = $items->reject->is_button;
    $buttons = $items->filter->is_button;
@endphp

<header x-data="{ mobile: false, mega: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 10"
        @scroll.window="scrolled = window.scrollY > 10"
        @keydown.escape.window="mega = false; mobile = false"
        class="sticky top-0 z-50">
    <div :class="scrolled ? 'shadow-[0_8px_30px_-12px_rgba(41,43,39,0.18)]' : ''"
         class="border-b border-charcoal/8 bg-ivory/95 backdrop-blur-md transition-shadow duration-300">
        {{-- Logo left · links centred · call to action right --}}
        <div class="container-site flex h-20 items-center justify-between gap-6 lg:grid lg:grid-cols-[1fr_auto_1fr]">
            <div class="lg:justify-self-start"><x-site.logo :name="$site['site_name']" /></div>

            {{-- Desktop navigation --}}
            <nav aria-label="Main" class="hidden lg:block">
                <ul class="flex items-center gap-1">
                    @foreach ($links as $item)
                        @if (rtrim($item->url, '/') === '/services')
                            <li class="relative" @mouseenter="mega = true" @mouseleave="mega = false">
                                <button type="button" @click="mega = !mega" :aria-expanded="mega" aria-controls="mega-menu"
                                        @class(['flex items-center gap-1 rounded-full px-4 py-2 text-sm font-medium transition hover:text-walnut', 'text-walnut' => $item->isActive()])>
                                    {{ $item->label }}
                                    <x-icon name="chevron-down" size="16" class="transition-transform duration-300" ::class="mega && 'rotate-180'" />
                                </button>
                                <x-site.mega-menu :services="$services" :overview="$item->href()" />
                            </li>
                        @else
                            <li>
                                <a href="{{ $item->href() }}" @if ($item->isActive()) aria-current="page" @endif
                                   @class(['relative rounded-full px-4 py-2 text-sm font-medium transition hover:text-walnut', 'text-walnut' => $item->isActive()])>
                                    {{ $item->label }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-3 lg:justify-self-end">
                @foreach ($buttons as $item)
                    <x-button :href="$item->href()" size="sm" icon="arrow-right" class="hidden lg:inline-flex">{{ $item->label }}</x-button>
                @endforeach

                <button type="button" class="grid size-11 place-items-center rounded-full border border-charcoal/15 lg:hidden"
                        @click="mobile = true" aria-label="Open menu" :aria-expanded="mobile" aria-controls="mobile-menu">
                    <x-icon name="menu" size="22" />
                </button>
            </div>
        </div>
    </div>

    <x-site.mobile-menu :site="$site" :items="$items" :services="$services" />
</header>
