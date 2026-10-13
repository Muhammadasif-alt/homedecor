@props(['name', 'label', 'checked' => false, 'hint' => null])

{{-- Boolean toggle. A hidden "0" makes unchecked boxes submit false. --}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = $attributes->get('id', 'f-'.str_replace('.', '-', $key));
    $isChecked = (bool) old($key, $checked);
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'flex items-start gap-3']) }}>
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1" @checked($isChecked)
           {{ $attributes->except(['class', 'id']) }}
           class="mt-0.5 size-4 shrink-0 rounded border-charcoal/30 accent-walnut focus-visible:outline-walnut">
    <div>
        <label for="{{ $id }}" class="text-sm font-medium text-charcoal">{{ $label }}</label>
        @if ($hint)<p class="text-xs text-charcoal-soft">{{ $hint }}</p>@endif
        @error($key)<p class="text-sm text-red-700">{{ $message }}</p>@enderror
    </div>
</div>
