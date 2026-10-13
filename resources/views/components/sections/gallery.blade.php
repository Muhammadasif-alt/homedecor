@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'white')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>
    <x-image-grid :images="$data['images']" class="mt-14" />
</x-section>
