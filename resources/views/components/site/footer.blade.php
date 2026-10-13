@props(['site', 'menus', 'services', 'areas'])

@php
    $socials = collect(['instagram', 'facebook', 'pinterest', 'youtube', 'tiktok'])
        ->mapWithKeys(fn ($network) => [$network => $site['social_'.$network] ?? null])
        ->filter();
    $phoneHref = ! empty($site['phone']) ? 'tel:'.preg_replace('/[^0-9+]/', '', $site['phone']) : null;
@endphp

<footer class="bg-charcoal text-ivory/80">
    <div class="container-site grid gap-12 py-20 sm:grid-cols-2 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <x-site.logo :name="$site['site_name']" tone="light" />
            @if (! empty($site['company_description']))
                <p class="mt-6 max-w-sm leading-relaxed">{{ $site['company_description'] }}</p>
            @endif
            @if ($socials->isNotEmpty())
                <ul class="mt-8 flex gap-3" aria-label="Social media">
                    @foreach ($socials as $network => $url)
                        <li>
                            <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"
                               class="grid size-10 place-items-center rounded-full border border-white/20 transition hover:border-sand hover:bg-sand hover:text-charcoal">
                                <x-icon :name="$network" size="18" />
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <x-site.footer-column title="Services" class="lg:col-span-3">
            @foreach ($services as $service)
                <li><a href="{{ route('services.show', $service) }}" class="transition hover:text-sand">{{ $service->name }}</a></li>
            @endforeach
        </x-site.footer-column>

        {{-- Services already have their own column, so the overview link is not repeated here. --}}
        <x-site.footer-column title="Quick links" class="lg:col-span-2">
            @foreach (($menus['header'] ?? collect())->where('is_button', false)->reject(fn ($item) => rtrim($item->url, '/') === '/services')->merge($menus['footer_quick'] ?? []) as $item)
                <li><a href="{{ $item->href() }}" class="transition hover:text-sand">{{ $item->label }}</a></li>
            @endforeach
        </x-site.footer-column>

        <x-site.footer-column title="Get in touch" class="lg:col-span-3">
            @if ($phoneHref)
                <li><a href="{{ $phoneHref }}" class="flex gap-3 transition hover:text-sand"><x-icon name="phone" size="18" class="mt-0.5 text-sand" />{{ $site['phone'] }}</a></li>
            @endif
            @if (! empty($site['email']))
                <li><a href="mailto:{{ $site['email'] }}" class="flex gap-3 break-all transition hover:text-sand"><x-icon name="mail" size="18" class="mt-0.5 text-sand" />{{ $site['email'] }}</a></li>
            @endif
            @if (! empty($site['address']))
                <li class="flex gap-3"><x-icon name="map-pin" size="18" class="mt-0.5 text-sand" /><span>{{ $site['address'] }}</span></li>
            @endif
            @if (! empty($site['business_hours']))
                <li class="flex gap-3"><x-icon name="clock" size="18" class="mt-0.5 text-sand" /><span class="whitespace-pre-line">{{ $site['business_hours'] }}</span></li>
            @endif
            @if ($areas->isNotEmpty())
                <li class="pt-2">
                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-sand">Service areas</span>
                    {{ $areas->pluck('name')->join(', ') }}
                </li>
            @elseif (! empty($site['service_area_text']))
                <li class="pt-2">{{ $site['service_area_text'] }}</li>
            @endif
            <li class="pt-3"><x-button :href="route('pages.show', 'book-consultation')" variant="light" size="sm" icon="arrow-right">Book a consultation</x-button></li>
        </x-site.footer-column>
    </div>

    <div class="border-t border-white/10">
        <div class="container-site flex flex-col gap-4 py-6 text-sm sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ $site['site_name'] }}. All rights reserved.@if (! empty($site['footer_note'])) {{ $site['footer_note'] }}@endif</p>
            <ul class="flex flex-wrap gap-x-6 gap-y-2">
                @foreach ($menus['footer_legal'] ?? [] as $item)
                    <li><a href="{{ $item->href() }}" class="transition hover:text-sand">{{ $item->label }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
