@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'white')">
    <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
        <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left">{{ strip_tags($section->body) }}</x-section-heading>
        <x-button :href="route('blog.index')" variant="outline" icon="arrow-right" class="shrink-0">All articles</x-button>
    </div>

    <div class="mt-14 grid gap-x-8 gap-y-14 md:grid-cols-3">
        @foreach ($data['posts'] as $post)
            <x-card.post :post="$post" />
        @endforeach
    </div>
</x-section>
