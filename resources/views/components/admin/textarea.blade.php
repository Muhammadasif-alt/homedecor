@props(['name', 'label' => null, 'value' => null, 'rows' => 4, 'hint' => null, 'counter' => null, 'required' => false])

@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = $attributes->get('id', 'f-'.str_replace('.', '-', $key));
    $current = (string) old($key, $value);
    $invalid = $errors->has($key);
    $classes = 'block w-full rounded border bg-white px-3 py-2 text-sm text-charcoal shadow-sm placeholder:text-charcoal/40 focus:outline-none focus:ring-2 focus:ring-walnut/25 '
        .($invalid ? 'border-red-400 focus:border-red-500' : 'border-charcoal/15 focus:border-walnut');
    $control = $attributes->except(['class', 'id'])->merge(['class' => $classes]);
@endphp

<x-admin.field :label="$label" :for="$id" :name="$name" :hint="$hint" :required="$required" {{ $attributes->only('class') }}>
    @if ($counter)
        <div x-data="{ v: @js($current) }" class="space-y-1">
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" x-model="v" @required($required)
                      @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $control }}></textarea>
            <p class="text-right text-xs" x-bind:class="v.length > {{ (int) $counter }} ? 'font-semibold text-red-700' : 'text-charcoal-soft'">
                <span x-text="v.length"></span> / {{ $counter }} characters
            </p>
        </div>
    @else
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
                  @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $control }}>{{ $current }}</textarea>
    @endif
</x-admin.field>
