{{-- Approved, genuine testimonials only; skipped entirely when there are none. --}}
@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'sand')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>

    <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($data['testimonials'] as $testimonial)
            <x-card.testimonial :testimonial="$testimonial" />
        @endforeach
    </div>

    @if ($section->get('limit') && ! request()->is('testimonials'))
        <div class="mt-12 text-center">
            <x-button :href="url('testimonials')" variant="outline" icon="arrow-right">Read all reviews</x-button>
        </div>
    @endif
</x-section>
