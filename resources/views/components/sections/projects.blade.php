@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'ivory')">
    <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
        <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left">{{ strip_tags($section->body) }}</x-section-heading>
        <x-button :href="route('projects.index')" variant="outline" icon="arrow-right" class="shrink-0">View portfolio</x-button>
    </div>

    <div class="mt-14 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($data['projects'] as $project)
            <x-card.project :project="$project" @class(['lg:translate-y-12' => $loop->index % 3 === 1]) data-reveal />
        @endforeach
    </div>
</x-section>
