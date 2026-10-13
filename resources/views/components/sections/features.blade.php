@props(['section', 'data' => [], 'first' => false])

@php
    $tone = $section->get('tone', 'sand');
    $columns = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-2 lg:grid-cols-3', '4' => 'sm:grid-cols-2 lg:grid-cols-4'][(string) $section->get('columns', count($section->get('items', [])) >= 4 ? 4 : 3)] ?? 'md:grid-cols-3';
    $light = $tone === 'charcoal' ? 'light' : 'dark';
@endphp

<x-section :tone="$tone">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" :tone="$light">{{ strip_tags($section->body) }}</x-section-heading>

    <div class="mt-16 grid gap-x-10 gap-y-14 {{ $columns }}">
        @foreach ($section->get('items', []) as $item)
            <x-card.feature :icon="$item['icon'] ?? null" :title="$item['title']" :tone="$light">{{ $item['text'] ?? '' }}</x-card.feature>
        @endforeach
    </div>
</x-section>
