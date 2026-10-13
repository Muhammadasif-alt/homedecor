@php
    $hero = $page->section('hero');
    $title = $activeCategory?->name ?? ($hero?->heading ?: $page->title);
@endphp

<x-layouts.app>
    <x-page-header :title="$title" :eyebrow="$activeCategory ? 'Blog category' : ($hero?->eyebrow ?? 'Journal')" :breadcrumbs="$seo->breadcrumbs">
        {{ $activeCategory?->description ?? strip_tags((string) $hero?->body) }}
    </x-page-header>

    <x-section tone="white">
        <div class="flex flex-col gap-6 border-b border-charcoal/10 pb-8 lg:flex-row lg:items-center lg:justify-between">
            <nav aria-label="Blog categories" class="-mx-5 overflow-x-auto px-5">
                <ul class="flex gap-2 whitespace-nowrap">
                    <li><a href="{{ route('blog.index') }}" @class(['inline-block rounded-full border px-4 py-2 text-sm transition', 'border-charcoal bg-charcoal text-ivory' => ! $activeCategory, 'border-charcoal/20 hover:border-charcoal' => $activeCategory])>All</a></li>
                    @foreach ($categories as $category)
                        <li><a href="{{ route('blog.category', $category) }}" @if ($activeCategory?->is($category)) aria-current="page" @endif
                               @class(['inline-block rounded-full border px-4 py-2 text-sm transition', 'border-charcoal bg-charcoal text-ivory' => $activeCategory?->is($category), 'border-charcoal/20 hover:border-charcoal' => ! $activeCategory?->is($category)])>{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <form action="{{ route('blog.index') }}" method="GET" role="search" class="relative w-full lg:w-80">
                <label for="blog-search" class="sr-only">Search articles</label>
                <input id="blog-search" type="search" name="q" value="{{ $search }}" placeholder="Search articles…"
                       class="w-full rounded-full border border-charcoal/15 bg-ivory py-3 pl-5 pr-12 focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/20">
                <button type="submit" class="absolute right-2 top-1/2 grid size-9 -translate-y-1/2 place-items-center rounded-full bg-charcoal text-ivory" aria-label="Search">
                    <x-icon name="search" size="16" />
                </button>
            </form>
        </div>

        @if ($search)
            <p class="mt-8 text-charcoal-soft">{{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} for “{{ $search }}”</p>
        @endif

        @if ($posts->isEmpty())
            <div class="py-20 text-center">
                <p class="font-serif text-3xl">No articles found.</p>
                <x-button :href="route('blog.index')" variant="outline" class="mt-6">Browse all articles</x-button>
            </div>
        @else
            <div class="mt-14 grid gap-x-8 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-card.post :post="$post" :heading-level="2" />
                @endforeach
            </div>
            <div class="mt-16">{{ $posts->links() }}</div>
        @endif
    </x-section>

    @push('head')
        @if ($posts->previousPageUrl())<link rel="prev" href="{{ $posts->previousPageUrl() }}">@endif
        @if ($posts->nextPageUrl())<link rel="next" href="{{ $posts->nextPageUrl() }}">@endif
    @endpush
</x-layouts.app>
