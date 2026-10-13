@php
    $site = \App\Models\Setting::all_values();
    $body = strtr((string) $page->body, [
        ':site_name' => e($site['site_name'] ?? config('app.name')),
        ':email' => e(($site['email'] ?? '') ?: 'our contact form'),
    ]);
@endphp

<x-layouts.app>
    <x-page-header :title="$page->title" :breadcrumbs="$seo->breadcrumbs">
        Last updated {{ $page->updated_at->format('j F Y') }}
    </x-page-header>

    <x-section tone="white">
        <x-rich-text :html="$body" class="mx-auto max-w-3xl" />
    </x-section>
</x-layouts.app>
