@props(['name', 'label' => null, 'value' => null, 'type' => 'text', 'hint' => null, 'counter' => null, 'required' => false])

{{-- Text-like input with label, inline error, optional live character counter (:counter="60"). --}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = $attributes->get('id', 'f-'.str_replace('.', '-', $key));
    $current = $type === 'password' ? null : old($key, $value);
    if ($current instanceof \DateTimeInterface) {
        $current = $current->format($type === 'datetime-local' ? 'Y-m-d\TH:i' : 'Y-m-d');
    }
    $invalid = $errors->has($key);
    $classes = 'block w-full rounded border bg-white px-3 py-2 text-sm text-charcoal shadow-sm placeholder:text-charcoal/40 focus:outline-none focus:ring-2 focus:ring-walnut/25 '
        .($invalid ? 'border-red-400 focus:border-red-500' : 'border-charcoal/15 focus:border-walnut');
    $control = $attributes->except(['class', 'id'])->merge(['class' => $classes]);
@endphp

<x-admin.field :label="$label" :for="$id" :name="$name" :hint="$hint" :required="$required" {{ $attributes->only('class') }}>
    @if ($counter)
        <div x-data="{ v: @js((string) $current) }" class="space-y-1">
            <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" x-model="v" @required($required)
                   @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $control }}>
            <p class="text-right text-xs" x-bind:class="v.length > {{ (int) $counter }} ? 'font-semibold text-red-700' : 'text-charcoal-soft'">
                <span x-text="v.length"></span> / {{ $counter }} characters
            </p>
        </div>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" @if (! is_null($current)) value="{{ $current }}" @endif @required($required)
               @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $control }}>
    @endif
</x-admin.field>
