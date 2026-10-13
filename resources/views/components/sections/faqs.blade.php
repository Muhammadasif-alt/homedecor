@props(['section', 'data' => [], 'first' => false])

<x-section :tone="$section->get('tone', 'ivory')">
    @if ($data['grouped'])
        <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>
        <div class="mx-auto mt-14 max-w-4xl space-y-14">
            @foreach ($data['faqs']->groupBy('category') as $category => $faqs)
                <div data-reveal>
                    <h2 class="mb-4 font-serif text-3xl">{{ \App\Models\Faq::CATEGORIES[$category] ?? ucfirst($category) }}</h2>
                    <x-accordion :items="$faqs" />
                </div>
            @endforeach
        </div>
    @else
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" align="left">{{ strip_tags($section->body) }}</x-section-heading>
                @unless (request()->is('faqs'))
                    <x-button :href="url('faqs')" variant="ghost" icon="arrow-right" class="mt-8">All questions</x-button>
                @endunless
            </div>
            <x-accordion :items="$data['faqs']" class="lg:col-span-8" />
        </div>
    @endif
</x-section>
