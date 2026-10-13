@props(['section', 'data' => [], 'first' => false])

@php $timeline = $section->get('layout') === 'timeline'; @endphp

@if ($timeline)
    <x-section :tone="$section->get('tone', 'ivory')">
        <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading">{{ strip_tags($section->body) }}</x-section-heading>
        <x-process-timeline :steps="$data['steps']" :media="$data['media']" class="mt-20" />
    </x-section>
@else
    <x-section tone="charcoal">
        <x-section-heading :eyebrow="$section->eyebrow" :heading="$section->heading" tone="light">{{ strip_tags($section->body) }}</x-section-heading>
        <ol class="mt-16 grid gap-px overflow-hidden rounded-sm bg-white/10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($data['steps'] as $step)
                <li class="bg-charcoal p-8 sm:p-10" data-reveal>
                    <div class="flex items-center justify-between">
                        <span class="font-serif text-5xl text-sand">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <x-icon :name="$step['icon'] ?? 'sparkles'" size="28" class="text-sand/70" />
                    </div>
                    <h3 class="mt-8 font-serif text-2xl text-white!">{{ $step['title'] }}</h3>
                    <p class="mt-3 leading-relaxed text-white/70">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
        @if (! request()->is('our-process'))
            <div class="mt-12 text-center">
                <x-button :href="url('our-process')" variant="light" icon="arrow-right">Our process in detail</x-button>
            </div>
        @endif
    </x-section>
@endif
