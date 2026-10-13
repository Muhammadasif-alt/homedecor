{{-- Lists real service areas configured in the CMS. Detail pages only for areas with unique content. --}}
@props(['section', 'data' => [], 'first' => false])

@php $text = \App\Models\Setting::get('service_area_text'); @endphp

<x-section :tone="$section->get('tone', 'white')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) ?: $text }}</x-section-heading>

    @if ($data['areas']->isNotEmpty())
        <ul class="mx-auto mt-12 flex max-w-4xl flex-wrap justify-center gap-3">
            @foreach ($data['areas'] as $area)
                <li>
                    @if ($area->has_page && filled($area->body))
                        <a href="{{ route('areas.show', $area) }}" class="inline-flex items-center gap-2 rounded-full border border-charcoal/20 bg-ivory px-5 py-2.5 transition hover:border-walnut hover:text-walnut">
                            <x-icon name="map-pin" size="16" class="text-walnut" />{{ $area->name }}
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full border border-charcoal/10 bg-ivory px-5 py-2.5">
                            <x-icon name="map-pin" size="16" class="text-walnut" />{{ $area->name }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</x-section>
