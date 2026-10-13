<x-layouts.app>
    <x-page-header :title="'Home Decor Services in '.$area->name" eyebrow="Service area" :breadcrumbs="$seo->breadcrumbs">
        {{ $area->summary }}
    </x-page-header>

    <x-section tone="white">
        <x-rich-text :html="$area->body" class="mx-auto max-w-3xl" />
    </x-section>

    <x-cta-banner :heading="'Planning a project in '.$area->name.'?'" body="Tell us about your home and we will arrange a consultation."
                  :buttons="[['label' => 'Book a consultation', 'url' => '/book-consultation'], ['label' => 'Our services', 'url' => '/services']]" />
</x-layouts.app>
