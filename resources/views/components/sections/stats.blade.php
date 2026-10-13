{{-- Only rendered when the admin has entered verified figures (SectionData returns null otherwise). --}}
@props(['section', 'data' => [], 'first' => false])

<section class="border-b border-charcoal/10 bg-white">
    <div class="container-site">
        <dl class="grid grid-cols-2 divide-charcoal/10 lg:grid-cols-4 lg:divide-x">
            @foreach ($section->get('items', []) as $item)
                <div class="px-4 py-10 text-center" data-reveal>
                    <dt class="order-2 mt-2 text-xs font-semibold uppercase tracking-[0.18em] text-charcoal-soft">{{ $item['label'] }}</dt>
                    <dd class="font-serif text-5xl text-walnut">{{ $item['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
