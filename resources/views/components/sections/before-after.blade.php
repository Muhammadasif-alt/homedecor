{{-- Rendered only when projects have genuine before + after photographs. --}}
@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'white')">
    <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>

    <div class="mt-14 grid gap-10 {{ count($data['projects']) > 1 ? 'lg:grid-cols-2' : 'mx-auto max-w-5xl' }}">
        @foreach ($data['projects'] as $project)
            <div data-reveal>
                <x-before-after :before="$project->beforeMedia" :after="$project->afterMedia" :label="$project->title" />
                <a href="{{ route('projects.show', $project) }}" class="mt-4 inline-flex items-center gap-2 font-serif text-xl hover:text-walnut">{{ $project->title }} <x-icon name="arrow-right" size="18" /></a>
            </div>
        @endforeach
    </div>
</x-section>
