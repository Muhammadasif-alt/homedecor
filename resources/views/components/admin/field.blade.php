@props(['label' => null, 'for' => null, 'name' => null, 'hint' => null, 'required' => false])

{{-- Label + control + inline error + hint. `name` may use bracket notation (items[0][title]). --}}
@php $errorKey = $name ? str_replace(['[]', '[', ']'], ['', '.', ''], $name) : null; @endphp

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if ($label)
        <label @if ($for) for="{{ $for }}" @endif class="block text-sm font-medium text-charcoal">
            {{ $label }}@if ($required)<span class="text-red-600" aria-hidden="true"> *</span>@endif
        </label>
    @endif
    {{ $slot }}
    @if ($errorKey)
        @error($errorKey)
            <p class="text-sm text-red-700" id="{{ $for }}-error">{{ $message }}</p>
        @enderror
    @endif
    @if ($hint)
        <p class="text-xs text-charcoal-soft">{{ $hint }}</p>
    @endif
</div>
