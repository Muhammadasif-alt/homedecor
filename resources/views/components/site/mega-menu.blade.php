@props(['services', 'overview'])

@php $featured = $services->first(fn ($s) => $s->heroMedia); @endphp

<div id="mega-menu" x-cloak x-show="mega"
     x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:leave="transition duration-150" x-transition:leave-end="opacity-0"
     class="fixed inset-x-0 top-20 pt-px">
    <div class="border-y border-charcoal/8 bg-white shadow-[0_30px_60px_-30px_rgba(41,43,39,0.35)]">
        <div class="container-site grid grid-cols-12 gap-10 py-10">
            <div class="col-span-8">
                <p class="eyebrow mb-6">Our services</p>
                <ul class="grid grid-cols-2 gap-x-8 gap-y-1">
                    @foreach ($services as $service)
                        <li>
                            <a href="{{ route('services.show', $service) }}" class="group flex items-start gap-4 rounded-lg p-3 transition hover:bg-ivory">
                                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-ivory text-walnut transition group-hover:bg-walnut group-hover:text-white">
                                    <x-icon :name="$service->icon ?? 'sparkles'" size="20" />
                                </span>
                                <span>
                                    <span class="block font-medium text-charcoal">{{ $service->name }}</span>
                                    <span class="mt-0.5 line-clamp-1 block text-sm text-charcoal-soft">{{ $service->short_description }}</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-span-4">
                <a href="{{ $overview }}" class="group relative block h-full min-h-72 overflow-hidden rounded-sm">
                    @if ($featured)
                        <x-picture :media="$featured->heroMedia" sizes="400px" class="absolute inset-0" imgClass="h-full w-full object-cover transition duration-700 group-hover:scale-105" />
                    @endif
                    <span class="absolute inset-0 bg-gradient-to-t from-charcoal/85 via-charcoal/20 to-transparent"></span>
                    <span class="absolute inset-x-0 bottom-0 p-6 text-white">
                        <span class="block font-serif text-2xl">Explore every service</span>
                        <span class="mt-2 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em]">View overview <x-icon name="arrow-right" size="16" /></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
