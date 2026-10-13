{{--
    The single, shared template for every service page (/services/{slug}).
    All content is loaded from the services table and its relations — nothing service-specific is hardcoded.
--}}
<x-layouts.app>
    {{-- 1–2. Hero + breadcrumbs --}}
    <x-detail-hero :media="$service->heroMedia" :title="$service->hero_title" :eyebrow="$service->name" :breadcrumbs="$seo->breadcrumbs">
        {{ $service->hero_description }}
        <x-slot:actions>
            <x-button :href="url('book-consultation').'?service='.$service->slug" variant="walnut" size="lg" icon="arrow-right">Book a consultation</x-button>
            <x-button href="#included" variant="light" size="lg">What's included</x-button>
        </x-slot:actions>
    </x-detail-hero>

    {{-- 3. Introduction --}}
    <x-section tone="white">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="eyebrow mb-4">{{ $service->name }}</p>
                <h2 class="text-3xl leading-snug sm:text-4xl">{{ $service->short_description }}</h2>
            </div>
            <x-rich-text :html="$service->intro" class="lg:col-span-7" data-reveal />
        </div>
    </x-section>

    {{-- 4. What's included --}}
    @if ($service->included)
        <x-section tone="ivory" id="included">
            <x-section-heading eyebrow="What's included" :heading="'Our '.$service->name.' service covers'" />
            <x-item-grid :items="$service->included" icon="check" class="mt-14" />
        </x-section>
    @endif

    {{-- Content sections (admin-managed, alternating image sides) --}}
    @foreach ($service->sections as $block)
        <x-section :tone="$loop->odd ? 'white' : 'ivory'">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
                @if ($block->media)
                    <div @class(['aspect-[4/3] overflow-hidden rounded-sm', 'lg:order-last' => $loop->even]) data-reveal>
                        <x-picture :media="$block->media" sizes="(min-width: 1024px) 45vw, 100vw" class="block h-full" />
                    </div>
                @endif
                <div @class(['lg:col-span-2 max-w-3xl' => ! $block->media])>
                    <h2 class="text-4xl leading-tight" data-reveal>{{ $block->heading }}</h2>
                    <x-rich-text :html="$block->body" class="mt-6" data-reveal />
                </div>
            </div>
        </x-section>
    @endforeach

    {{-- 5. Decoration styles & options --}}
    @if ($service->styles)
        <x-section tone="charcoal">
            <x-section-heading eyebrow="Styles & options" heading="Choose the look that feels like home" tone="light" />
            <div @class(['mt-14 grid gap-6 sm:grid-cols-2', 'lg:grid-cols-4' => count($service->styles) === 4, 'lg:grid-cols-3' => count($service->styles) !== 4])>
                @foreach ($service->styles as $style)
                    <div class="rounded-sm border border-white/15 p-8" data-reveal>
                        <h3 class="font-serif text-2xl text-white!">{{ $style['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-white/70">{{ $style['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 6. Benefits --}}
    @if ($service->benefits)
        <x-section tone="white">
            <x-section-heading eyebrow="Benefits" heading="Why it's worth bringing in a specialist" />
            <div class="mt-14 grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($service->benefits as $benefit)
                    <x-card.feature :number="$loop->iteration" :title="$benefit['title']">{{ $benefit['text'] }}</x-card.feature>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 7. Customisation & materials (with gallery) --}}
    @if ($service->materials)
        <x-section tone="sand">
            <div class="grid gap-14 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <x-section-heading eyebrow="Customisation" heading="Materials, finishes and personal touches" align="left" />
                    <dl class="mt-10 space-y-6">
                        @foreach ($service->materials as $material)
                            <div class="border-t border-charcoal/15 pt-6" data-reveal>
                                <dt class="font-serif text-2xl">{{ $material['title'] }}</dt>
                                <dd class="mt-2 leading-relaxed text-charcoal-soft">{{ $material['text'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
                @if ($service->gallery->isNotEmpty())
                    <div class="lg:col-span-7">
                        <x-image-grid :images="$service->gallery" layout="feature" :limit="3" class="lg:sticky lg:top-28" />
                    </div>
                @endif
            </div>
        </x-section>
    @elseif ($service->gallery->isNotEmpty())
        <x-section tone="sand"><x-image-grid :images="$service->gallery" layout="feature" class="mx-auto max-w-5xl" /></x-section>
    @endif

    {{-- 8. Process --}}
    @if ($process)
        <x-section tone="ivory">
            <x-section-heading eyebrow="How it works" heading="Our step-by-step process" />
            <x-process-timeline :steps="$process" class="mt-20" />
        </x-section>
    @endif

    {{-- 9. Relevant projects --}}
    @if ($service->projects->isNotEmpty())
        <x-section tone="white">
            <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
                <x-section-heading eyebrow="Portfolio" :heading="$service->name.' projects'" align="left" />
                <x-button :href="route('projects.index')" variant="outline" icon="arrow-right" class="shrink-0">Full portfolio</x-button>
            </div>
            <div class="mt-14 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($service->projects->take(6) as $project)
                    <x-card.project :project="$project" data-reveal />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 10. Before & After (genuine pairs only) --}}
    @php $transformations = $service->projects->filter->hasBeforeAfter(); @endphp
    @if ($transformations->isNotEmpty())
        <x-section tone="ivory">
            <x-section-heading eyebrow="Transformations" heading="Before & after" />
            <div class="mx-auto mt-14 grid max-w-5xl gap-10">
                @foreach ($transformations->take(2) as $project)
                    <x-before-after :before="$project->beforeMedia" :after="$project->afterMedia" :label="$project->title" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 11. Why choose us --}}
    <x-why-choose-us />

    {{-- 12. Testimonials (genuine, approved only) --}}
    @if ($service->testimonials->isNotEmpty())
        <x-section tone="sand">
            <x-section-heading eyebrow="Client words" heading="What our clients say" />
            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($service->testimonials->take(3) as $testimonial)
                    <x-card.testimonial :testimonial="$testimonial" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 13. Service FAQs --}}
    @if ($service->faqs->isNotEmpty())
        <x-section tone="white">
            <div class="grid gap-12 lg:grid-cols-12">
                <x-section-heading eyebrow="FAQs" :heading="'Questions about '.strtolower($service->name)" align="left" class="lg:col-span-4" />
                <x-accordion :items="$service->faqs" class="lg:col-span-8" />
            </div>
        </x-section>
    @endif

    {{-- 14. Further reading (related journal articles) --}}
    @if ($articles->isNotEmpty())
        <x-section tone="white">
            <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
                <x-section-heading eyebrow="From the journal" heading="Further reading" align="left" />
                <x-button :href="route('blog.index')" variant="outline" icon="arrow-right" class="shrink-0">All articles</x-button>
            </div>
            <div class="mt-14 grid gap-x-8 gap-y-14 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $post)
                    <x-card.post :post="$post" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 15. Related services --}}
    @if ($service->related->isNotEmpty())
        <x-section tone="ivory">
            <x-section-heading eyebrow="Explore more" heading="Related home decor services" />
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($service->related as $related)
                    <x-card.service :service="$related" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- 16. Final CTA --}}
    <x-cta-banner :media="$service->gallery->last() ?? $service->heroMedia" eyebrow="Let's begin"
                  :heading="'Ready to transform your space?'"
                  :body="'Tell us about your home and we will put together a personalised '.strtolower($service->name).' proposal.'"
                  :buttons="[['label' => 'Request a personalised quote', 'url' => '/book-consultation?service='.$service->slug], ['label' => 'Contact us', 'url' => '/contact']]" />
</x-layouts.app>
