@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'white')" id="services">
    <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
        <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left">{{ strip_tags($section->body) }}</x-section-heading>
        @if ($section->get('limit'))
            <x-button :href="route('services.index')" variant="outline" icon="arrow-right" class="shrink-0">All services</x-button>
        @endif
    </div>

    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($data['services'] as $service)
            <x-card.service :service="$service" />
        @endforeach
    </div>
</x-section>
