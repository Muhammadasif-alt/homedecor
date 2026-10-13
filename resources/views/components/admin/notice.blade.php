@props(['tone' => 'info'])

{{-- Inline guidance box (editorial notes, warnings). --}}
@php
    $classes = [
        'info' => 'border-sand bg-sand-light/40 text-charcoal',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-900',
    ][$tone];
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-lg border px-4 py-3 text-sm {$classes}"]) }} role="note">
    <x-admin.icon :name="$tone === 'warning' ? 'alert' : 'info'" class="mt-0.5 size-5 shrink-0 text-walnut" />
    <div class="space-y-1">{{ $slot }}</div>
</div>
