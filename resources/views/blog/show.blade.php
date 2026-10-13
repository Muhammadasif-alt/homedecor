{{-- The single, shared template for every blog article (/blog/{slug}). --}}
<x-layouts.app>
    <article>
        <header class="bg-ivory-dark pb-12 pt-14 sm:pt-16">
            <div class="container-site max-w-4xl">
                <x-breadcrumbs :items="$seo->breadcrumbs" class="mb-8" />
                @if ($post->categories->isNotEmpty())
                    <p class="flex flex-wrap gap-3">
                        @foreach ($post->categories as $category)
                            <a href="{{ route('blog.category', $category) }}" class="eyebrow hover:text-charcoal">{{ $category->name }}</a>
                        @endforeach
                    </p>
                @endif
                <h1 class="mt-4 text-4xl leading-[1.08] sm:text-6xl">{{ $post->title }}</h1>
                <p class="mt-6 text-xl leading-relaxed text-charcoal-soft">{{ $post->excerpt }}</p>
                <p class="mt-8 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-charcoal-soft">
                    @if ($post->author)<span>By <strong class="font-semibold text-charcoal">{{ $post->author->name }}</strong></span><span aria-hidden="true">·</span>@endif
                    <span>Published <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j F Y') }}</time></span>
                    @if ($post->updated_at->gt($post->published_at?->copy()->addDay() ?? now()))
                        <span aria-hidden="true">·</span><span>Updated <time datetime="{{ $post->updated_at->toDateString() }}">{{ $post->updated_at->format('j F Y') }}</time></span>
                    @endif
                    <span aria-hidden="true">·</span><span>{{ $post->readingMinutes() }} min read</span>
                </p>
            </div>
        </header>

        @if ($post->featuredMedia)
            <div class="bg-gradient-to-b from-ivory-dark from-50% to-white to-50%">
                <div class="container-site max-w-6xl">
                    <x-picture :media="$post->featuredMedia" :priority="true" sizes="(min-width: 1152px) 1152px, 100vw" class="block aspect-[16/9] overflow-hidden rounded-sm" />
                </div>
            </div>
        @endif

        <div class="bg-white py-16 sm:py-20">
            <div class="container-site grid gap-12 lg:grid-cols-12">
                @if ($toc)
                    <aside class="lg:col-span-3">
                        <nav aria-label="Table of contents" class="rounded-sm bg-ivory p-6 lg:sticky lg:top-28">
                            <p class="eyebrow mb-4">In this article</p>
                            <ol class="space-y-2.5 text-sm">
                                @foreach ($toc as $item)
                                    <li><a href="#{{ $item['id'] }}" class="text-charcoal-soft transition hover:text-walnut">{{ $item['text'] }}</a></li>
                                @endforeach
                            </ol>
                        </nav>
                    </aside>
                @endif

                <div @class(['lg:col-span-8 lg:col-start-5' => $toc, 'mx-auto max-w-3xl lg:col-span-12' => ! $toc])>
                    <div class="prose-decor [&_h2]:scroll-mt-28">{!! $body !!}</div>

                    @if ($post->service)
                        <aside class="mt-16 grid overflow-hidden rounded-sm bg-charcoal text-ivory sm:grid-cols-5">
                            @if ($post->service->heroMedia)
                                <x-picture :media="$post->service->heroMedia" sizes="300px" class="block aspect-[4/3] sm:col-span-2 sm:aspect-auto" />
                            @endif
                            <div class="p-8 sm:col-span-3">
                                <p class="eyebrow text-sand!">Related service</p>
                                <p class="mt-3 font-serif text-3xl text-white">{{ $post->service->name }}</p>
                                <p class="mt-3 text-white/75">{{ $post->service->short_description }}</p>
                                <x-button :href="route('services.show', $post->service)" variant="light" size="sm" icon="arrow-right" class="mt-6">Explore the service</x-button>
                            </div>
                        </aside>
                    @endif
                </div>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <x-section tone="ivory">
            <x-section-heading eyebrow="Keep reading" heading="Related articles" />
            <div class="mt-14 grid gap-x-8 gap-y-14 md:grid-cols-3">
                @foreach ($related as $item)
                    <x-card.post :post="$item" />
                @endforeach
            </div>
        </x-section>
    @endif

    <x-cta-banner :media="$post->featuredMedia" eyebrow="Love these ideas?" heading="Let us bring them to your home"
                  body="Book a consultation and we will tailor a decor plan to your space, style and budget."
                  :buttons="[['label' => 'Book a consultation', 'url' => '/book-consultation'], ['label' => 'Our services', 'url' => '/services']]" />
</x-layouts.app>
