{{--
    Renders a page's enabled sections in order. Each section type maps to
    components/sections/{type}.blade.php. Sections with no real data are skipped.
--}}
@props(['page'])

@php
    $resolver = app(\App\View\SectionData::class);
    // The first section after the announcement bar owns the page's H1.
    $lead = $page->enabledSections->first(fn ($s) => $s->type !== 'announcement');
@endphp

@foreach ($page->enabledSections as $section)
    @php $data = $resolver->resolve($section); @endphp
    @continue($data === null)
    <x-dynamic-component :component="'sections.'.str_replace('_', '-', $section->type)"
                         :section="$section" :data="$data" :first="$lead?->is($section)" />
@endforeach
