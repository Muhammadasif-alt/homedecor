{{-- Two-column edit layout: main content + a sidebar (publish options, images). --}}
<div {{ $attributes->merge(['class' => 'grid items-start gap-6 lg:grid-cols-3']) }}>
    <div class="space-y-6 lg:col-span-2">{{ $slot }}</div>
    <aside class="space-y-6">{{ $aside ?? '' }}</aside>
</div>
