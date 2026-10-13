{{--
    Services dropdown: icon + name only, in three columns. Positioned against the sticky header
    (not the "Services" link) so the wide panel stays centred and never runs off-screen.
--}}
@props(['services', 'overview'])

<div id="mega-menu" x-cloak x-show="mega"
     x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-1"
     x-transition:leave="transition duration-150" x-transition:leave-end="opacity-0"
     class="absolute left-1/2 top-full w-[min(62rem,calc(100vw-3rem))] -translate-x-1/2 pt-2">
    <div class="rounded-lg border border-charcoal/8 bg-white p-4 shadow-[0_30px_60px_-30px_rgba(41,43,39,0.35)]">
        <ul class="grid grid-cols-3 gap-x-2 gap-y-1">
            @foreach ($services as $service)
                <li>
                    <a href="{{ route('services.show', $service) }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium leading-snug text-charcoal transition hover:bg-ivory hover:text-walnut">
                        <span class="grid size-8 shrink-0 place-items-center rounded-full bg-ivory text-walnut transition group-hover:bg-walnut group-hover:text-white">
                            <x-icon :name="$service->icon ?? 'sparkles'" size="16" />
                        </span>
                        {{ $service->name }}
                    </a>
                </li>
            @endforeach
        </ul>
        <a href="{{ $overview }}" class="mt-3 flex items-center justify-between rounded-md border-t border-charcoal/8 px-3 pt-3 pb-1 text-xs font-semibold uppercase tracking-[0.16em] text-walnut hover:text-charcoal">
            View all {{ $services->count() }} services <x-icon name="arrow-right" size="16" />
        </a>
    </div>
</div>
