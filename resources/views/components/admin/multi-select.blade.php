@props(['name', 'label' => null, 'options' => [], 'selected' => [], 'hint' => null, 'empty' => 'No options available yet.'])

{{-- Accessible multi-select rendered as a filterable checkbox list. Submits name[]. --}}
@php
    $key = str_replace(['[]', '[', ']'], ['', '.', ''], $name);
    $chosen = collect(old($key, $selected) ?: [])->map(fn ($v) => (string) $v)->all();
@endphp

<fieldset {{ $attributes->merge(['class' => 'space-y-1.5']) }} x-data="{ q: '' }">
    @if ($label)<legend class="mb-1.5 text-sm font-medium text-charcoal">{{ $label }}</legend>@endif
    <input type="hidden" name="{{ $key }}" value="">
    @if (count($options) > 8)
        <input type="search" x-model="q" placeholder="Filter…" aria-label="Filter {{ $label }}"
               class="block w-full rounded border border-charcoal/15 bg-white px-3 py-1.5 text-sm focus:border-walnut focus:outline-none">
    @endif
    <div class="max-h-56 space-y-0.5 overflow-y-auto rounded border border-charcoal/15 bg-white p-2">
        @forelse ($options as $value => $optionLabel)
            <label class="flex cursor-pointer items-center gap-2 rounded px-2 py-1 text-sm hover:bg-ivory"
                   x-show="! q || {{ \Illuminate\Support\Js::from(mb_strtolower($optionLabel)) }}.includes(q.toLowerCase())">
                <input type="checkbox" name="{{ $key }}[]" value="{{ $value }}" @checked(in_array((string) $value, $chosen, true))
                       class="size-4 rounded border-charcoal/30 accent-walnut">
                <span>{{ $optionLabel }}</span>
            </label>
        @empty
            <p class="px-2 py-1 text-sm text-charcoal-soft">{{ $empty }}</p>
        @endforelse
    </div>
    @error($key)<p class="text-sm text-red-700">{{ $message }}</p>@enderror
    @error($key.'.*')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
    @if ($hint)<p class="text-xs text-charcoal-soft">{{ $hint }}</p>@endif
</fieldset>
