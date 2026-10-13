@props(['name', 'label' => null, 'options' => [], 'value' => null, 'placeholder' => null, 'hint' => null, 'required' => false])

{{-- :options is [value => label]. --}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = $attributes->get('id', 'f-'.str_replace('.', '-', $key));
    $current = (string) old($key, $value);
    $invalid = $errors->has($key);
    $classes = 'block w-full rounded border bg-white px-3 py-2 text-sm text-charcoal shadow-sm focus:outline-none focus:ring-2 focus:ring-walnut/25 '
        .($invalid ? 'border-red-400 focus:border-red-500' : 'border-charcoal/15 focus:border-walnut');
@endphp

<x-admin.field :label="$label" :for="$id" :name="$name" :hint="$hint" :required="$required" {{ $attributes->only('class') }}>
    <select id="{{ $id }}" name="{{ $name }}" @required($required)
            @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            {{ $attributes->except(['class', 'id'])->merge(['class' => $classes]) }}>
        @if (! is_null($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($current === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</x-admin.field>
