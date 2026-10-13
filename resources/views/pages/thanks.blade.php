<x-layouts.app>
    <x-section tone="ivory" spacing="loose">
        <div class="mx-auto max-w-2xl text-center">
            <span class="mx-auto grid size-20 place-items-center rounded-full bg-walnut text-white"><x-icon name="check" size="36" stroke="2" /></span>
            <h1 class="mt-8 text-5xl">Thank you</h1>
            <p class="mt-5 text-lg text-charcoal-soft">
                @if ($type === 'consultation')
                    Your consultation request has been received. We will be in touch shortly to arrange a time that suits you.
                @else
                    Your message has been received. We will reply as soon as possible.
                @endif
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <x-button :href="route('projects.index')" icon="arrow-right">Browse our portfolio</x-button>
                <x-button :href="route('blog.index')" variant="outline">Read decor ideas</x-button>
            </div>
        </div>
    </x-section>
</x-layouts.app>
