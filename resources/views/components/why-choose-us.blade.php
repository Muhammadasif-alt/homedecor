{{--
    Reuses the "Why choose us" features block from the Home page CMS (first `features` section whose
    heading mentions "why"), so the copy is edited in one place.
--}}
@php
    $block = \Illuminate\Support\Facades\Cache::remember('why-choose-us', 3600, fn () => \App\Models\PageSection::query()
        ->where('type', 'features')->where('is_enabled', true)
        ->whereHas('page', fn ($q) => $q->where('slug', 'home'))
        ->where(fn ($q) => $q->where('eyebrow', 'like', '%why%')->orWhere('heading', 'like', '%why%'))->first());
@endphp

@if ($block)
    <x-sections.features :section="$block" />
@endif
