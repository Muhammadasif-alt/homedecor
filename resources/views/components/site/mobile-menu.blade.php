@props(['site', 'items', 'services'])

<div id="mobile-menu" x-cloak x-show="mobile" class="fixed inset-0 z-[60] lg:hidden" role="dialog" aria-modal="true" aria-label="Menu">
    <div x-show="mobile" x-transition.opacity class="absolute inset-0 bg-charcoal/50" @click="mobile = false"></div>

    <div x-show="mobile" x-trap.noscroll="mobile"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-end="translate-x-full"
         class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col overflow-y-auto bg-ivory">
        <div class="flex h-20 items-center justify-between border-b border-charcoal/10 px-6">
            <x-site.logo :name="$site['site_name']" />
            <button type="button" class="grid size-11 place-items-center rounded-full border border-charcoal/15" @click="mobile = false" aria-label="Close menu">
                <x-icon name="x" size="22" />
            </button>
        </div>

        <nav aria-label="Mobile" class="flex-1 px-6 py-6">
            <ul class="divide-y divide-charcoal/10">
                @foreach ($items->where('is_button', false) as $item)
                    @if (rtrim($item->url, '/') === '/services')
                        <li x-data="{ open: false }">
                            <button type="button" @click="open = !open" :aria-expanded="open" class="flex w-full items-center justify-between py-4 font-serif text-2xl">
                                {{ $item->label }}
                                <x-icon name="chevron-down" size="20" class="transition-transform" ::class="open && 'rotate-180'" />
                            </button>
                            <ul x-show="open" x-collapse class="space-y-1 pb-4">
                                <li><a href="{{ $item->href() }}" class="block py-2 text-sm font-semibold uppercase tracking-[0.14em] text-walnut">All services</a></li>
                                @foreach ($services as $service)
                                    <li><a href="{{ route('services.show', $service) }}" class="block py-2 text-charcoal-soft hover:text-walnut">{{ $service->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{ $item->href() }}" @if ($item->isActive()) aria-current="page" @endif
                               @class(['block py-4 font-serif text-2xl', 'text-walnut' => $item->isActive()])>{{ $item->label }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <div class="space-y-3 border-t border-charcoal/10 p-6">
            @foreach ($items->where('is_button', true) as $item)
                <x-button :href="$item->href()" class="w-full" icon="arrow-right">{{ $item->label }}</x-button>
            @endforeach
            @if (! empty($site['phone']))
                <x-button :href="'tel:'.preg_replace('/[^0-9+]/', '', $site['phone'])" variant="outline" class="w-full" icon="phone">Call us</x-button>
            @endif
        </div>
    </div>
</div>
