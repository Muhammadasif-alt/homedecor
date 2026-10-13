@props(['title'])

<div {{ $attributes }}>
    <h2 class="mb-6 font-sans text-xs font-semibold uppercase tracking-[0.2em] text-sand">{{ $title }}</h2>
    <ul class="space-y-3 text-sm">
        {{ $slot }}
    </ul>
</div>
