@php
    $hero = $page->section('hero');
    $title = $activeCategory?->name ?? ($hero?->heading ?: $page->title);
@endphp

<x-layouts.app>
    <x-page-header :title="$title" :eyebrow="$activeCategory ? 'Blog category' : ($hero?->eyebrow ?? 'Journal')" :breadcrumbs="$seo->breadcrumbs">
        {{ $activeCategory?->description ?? strip_tags((string) $hero?->body) }}
    </x-page-header>

    <x-section tone="white">
        @php
            $pill = 'group inline-flex items-center gap-2.5 rounded-full border py-2 pl-4 pr-3 text-sm font-medium transition duration-300';
            $on = 'border-walnut bg-walnut text-white shadow-[0_10px_24px_-12px_rgba(118,98,77,0.8)]';
            $off = 'border-charcoal/12 bg-white text-charcoal hover:-translate-y-0.5 hover:border-walnut/40 hover:bg-ivory hover:text-walnut';
            $badge = 'grid min-w-6 place-items-center rounded-full px-1.5 py-0.5 text-[0.7rem] font-semibold';
        @endphp

        {{-- Filter bar: topic pills wrap (no scrollbar) beside a refined search field --}}
        <div class="rounded-2xl border border-charcoal/8 bg-ivory/60 p-5 sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="eyebrow">Browse by topic</p>
                    <p class="mt-1 text-sm text-charcoal-soft">Ideas and advice for every room in your home.</p>
                </div>

                <form action="{{ route('blog.index') }}" method="GET" role="search" class="group relative w-full lg:w-96">
                    <label for="blog-search" class="sr-only">Search articles</label>
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-charcoal/40 transition group-focus-within:text-walnut">
                        <x-icon name="search" size="18" />
                    </span>
                    <input id="blog-search" type="search" name="q" value="{{ $search }}" placeholder="Search articles…"
                           class="w-full rounded-full border border-charcoal/12 bg-white py-3.5 pl-11 pr-28 text-sm shadow-[0_8px_24px_-16px_rgba(41,43,39,0.35)] transition placeholder:text-charcoal/40 focus:border-walnut focus:outline-none focus:ring-4 focus:ring-walnut/10">
                    <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-full bg-charcoal px-5 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-ivory transition hover:bg-walnut">
                        Search
                    </button>
                </form>
            </div>

            <nav aria-label="Blog categories" class="mt-5 border-t border-charcoal/8 pt-5">
                <ul class="flex flex-wrap gap-2.5">
                    <li>
                        <a href="{{ route('blog.index') }}" @if (! $activeCategory) aria-current="page" @endif class="{{ $pill }} {{ $activeCategory ? $off : $on }}">
                            <x-icon name="sparkles" size="14" /> All articles
                        </a>
                    </li>
                    @foreach ($categories as $category)
                        @php $active = $activeCategory?->is($category); @endphp
                        <li>
                            <a href="{{ route('blog.category', $category) }}" @if ($active) aria-current="page" @endif class="{{ $pill }} {{ $active ? $on : $off }}">
                                {{ $category->name }}
                                <span class="{{ $badge }} {{ $active ? 'bg-white/20 text-white' : 'bg-ivory-dark text-charcoal-soft group-hover:bg-white' }}">{{ $category->posts_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
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
