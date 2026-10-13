@php app(\App\Support\Seo::class)->title('Page not found')->noindex(); @endphp

<x-layouts.app>
    <x-section tone="ivory" spacing="loose">
        <div class="mx-auto max-w-2xl text-center">
            <p class="font-serif text-8xl text-sand">404</p>
            <h1 class="mt-4 text-5xl">This room seems to be empty</h1>
            <p class="mt-5 text-lg text-charcoal-soft">The page you are looking for may have moved or no longer exists.</p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <x-button :href="route('home')" icon="arrow-right">Back to home</x-button>
                <x-button :href="route('services.index')" variant="outline">Our services</x-button>
                <x-button :href="route('pages.show', 'contact')" variant="outline">Contact us</x-button>
            </div>
        </div>
    </x-section>
</x-layouts.app>
