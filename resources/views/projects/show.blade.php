{{-- The single, shared template for every portfolio project (/gallery/{slug}). --}}
@php
    $gallery = $project->images->pluck('media')->filter();
    $captions = $project->images->pluck('caption', 'media_id')->filter()->all();
    $facts = array_filter([
        'Category' => $project->category?->name,
        'Location' => $project->location,
        'Services' => $project->services->pluck('name')->join(', ') ?: null,
    ]);
    $story = array_filter([
        'Project overview' => $project->overview,
        'Client requirements' => $project->requirements,
        'Design solution' => $project->solution,
        'Materials & styling' => $project->materials,
    ]);
@endphp

<x-layouts.app>
    <x-detail-hero :media="$project->cover" :title="$project->title" :eyebrow="$project->category?->name ?? 'Portfolio'" :breadcrumbs="$seo->breadcrumbs">
        {{ $project->summary }}
    </x-detail-hero>

    <x-section tone="white">
        <div class="grid gap-14 lg:grid-cols-12">
            @if ($facts)
                <aside class="lg:col-span-4">
                    <dl class="space-y-6 rounded-sm bg-ivory p-8 lg:sticky lg:top-28">
                        @foreach ($facts as $label => $value)
                            <div>
                                <dt class="eyebrow">{{ $label }}</dt>
                                <dd class="mt-1 text-lg">{{ $value }}</dd>
                            </div>
                        @endforeach
                        <x-button :href="url('book-consultation')" class="w-full" icon="arrow-right">Start a similar project</x-button>
                    </dl>
                </aside>
            @endif

            <div class="space-y-14 lg:col-span-8">
                @foreach ($story as $heading => $html)
                    <div data-reveal>
                        <h2 class="text-3xl sm:text-4xl">{{ $heading }}</h2>
                        <x-rich-text :html="$html" class="mt-5" />
                    </div>
                @endforeach
            </div>
        </div>
    </x-section>

    @if ($gallery->isNotEmpty())
        <x-section tone="sand">
            <x-section-heading eyebrow="Gallery" heading="Project photographs" />
            <x-image-grid :images="$gallery" :captions="$captions" class="mt-14" />
        </x-section>
    @endif

    @if ($project->hasBeforeAfter())
        <x-section tone="white">
            <x-section-heading eyebrow="Transformation" heading="Before & after" />
            <div class="mx-auto mt-14 max-w-5xl">
                <x-before-after :before="$project->beforeMedia" :after="$project->afterMedia" :label="$project->title" />
            </div>
        </x-section>
    @endif

    @if ($project->testimonials->isNotEmpty())
        <x-section tone="ivory">
            <x-section-heading eyebrow="Client feedback" heading="In their words" />
            <div class="mx-auto mt-14 grid max-w-4xl gap-6">
                @foreach ($project->testimonials as $testimonial)
                    <x-card.testimonial :testimonial="$testimonial" />
                @endforeach
            </div>
        </x-section>
    @endif

    @if ($project->services->isNotEmpty())
        <x-section tone="white" spacing="tight">
            <div class="flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left">
                <h2 class="text-3xl">Services used in this project</h2>
                <ul class="flex flex-wrap justify-center gap-3">
                    @foreach ($project->services as $service)
                        <li><x-button :href="route('services.show', $service)" variant="outline" size="sm" icon="arrow-right">{{ $service->name }}</x-button></li>
                    @endforeach
                </ul>
            </div>
        </x-section>
    @endif

    @if ($related->isNotEmpty())
        <x-section tone="ivory">
            <x-section-heading eyebrow="More work" heading="Related projects" />
            <div class="mt-14 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $item)
                    <x-card.project :project="$item" data-reveal />
                @endforeach
            </div>
        </x-section>
    @endif

    <x-cta-banner :media="$gallery->last() ?? $project->cover" eyebrow="Inspired?" heading="Request a similar transformation"
                  body="Share your space and your ideas — we will shape a design around the way you live."
                  :buttons="[['label' => 'Book a consultation', 'url' => '/book-consultation'], ['label' => 'View all projects', 'url' => '/gallery']]" />
</x-layouts.app>
