@props(['section', 'data' => [], 'first' => false])

@php
    $site = \App\Models\Setting::all_values();
    $phone = $site['phone'] ?? null;
@endphp

<div class="bg-charcoal text-xs text-ivory/85">
    <div class="container-site flex flex-col items-center justify-between gap-2 py-2.5 text-center sm:flex-row sm:text-left">
        <p class="flex flex-wrap items-center justify-center gap-x-5 gap-y-1">
            @if ($section->body)<span>{{ strip_tags($section->body) }}</span>@endif
            @if (! empty($site['service_area_text']))<span class="hidden items-center gap-1.5 md:inline-flex"><x-icon name="map-pin" size="14" class="text-sand" />{{ $site['service_area_text'] }}</span>@endif
        </p>
        <p class="flex items-center gap-5">
            @if ($phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="inline-flex items-center gap-1.5 hover:text-sand"><x-icon name="phone" size="14" class="text-sand" />{{ $phone }}</a>
            @endif
            @if ($section->get('link_url'))
                <a href="{{ $section->get('link_url') }}" class="inline-flex items-center gap-1.5 font-semibold uppercase tracking-[0.14em] text-sand hover:text-white">
                    {{ $section->get('link_label', 'Book a consultation') }} <x-icon name="arrow-right" size="14" />
                </a>
            @endif
        </p>
    </div>
</div>
