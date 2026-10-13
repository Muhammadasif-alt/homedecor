{{-- Filterable portfolio grid with "load more" (all projects are server-rendered for SEO). --}}
@props(['section', 'data' => [], 'first' => false])

@php $pageSize = (int) $section->get('page_size', 9); @endphp

<x-section :tone="$section->get('tone', 'ivory')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>

    <div x-data="galleryFilter({{ $pageSize }})" class="mt-12">
        <div class="flex flex-wrap justify-center gap-2" role="group" aria-label="Filter projects by category">
            <button type="button" @click="select('all')" :aria-pressed="active === 'all'"
                    :class="active === 'all' ? 'bg-charcoal text-ivory border-charcoal' : 'border-charcoal/20 hover:border-charcoal'"
                    class="rounded-full border px-5 py-2 text-sm font-medium transition">All</button>
            @foreach ($data['categories'] as $category)
                <button type="button" @click="select('{{ $category->slug }}')" :aria-pressed="active === '{{ $category->slug }}'"
                        :class="active === '{{ $category->slug }}' ? 'bg-charcoal text-ivory border-charcoal' : 'border-charcoal/20 hover:border-charcoal'"
                        class="rounded-full border px-5 py-2 text-sm font-medium transition">{{ $category->name }}</button>
            @endforeach
        </div>

        @php $positions = []; @endphp
        <ul class="mt-12 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3" aria-live="polite">
            @foreach ($data['projects'] as $project)
                @php
                    $slug = $project->category?->slug ?? 'other';
                    $all = $loop->index;
                    $inCategory = $positions[$slug] = ($positions[$slug] ?? -1) + 1;
                @endphp
                <li x-show="active === 'all' ? {{ $all }} < visible : (active === '{{ $slug }}' && {{ $inCategory }} < visible)"
                    x-transition.opacity.duration.400ms
                    @if ($all >= $pageSize) x-cloak @endif>
                    <x-card.project :project="$project" />
                </li>
            @endforeach
        </ul>

        @php $counts = collect($positions)->map(fn ($p) => $p + 1)->put('all', count($data['projects'])); @endphp
        <div class="mt-14 text-center" x-show="visible < (@js($counts)[active] ?? 0)" x-cloak>
            <x-button type="button" variant="outline" @click="more()">Load more projects</x-button>
        </div>
    </div>
</x-section>
