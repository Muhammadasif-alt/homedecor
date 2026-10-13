{{--
    Labelled form control with error message. Supports input types, textarea and select.
    <x-form.field name="email" label="Email" type="email" required />
    <x-form.field name="budget" label="Budget" type="select" :options="$options" placeholder="Choose…" />
--}}
@props(['name', 'label', 'type' => 'text', 'options' => [], 'placeholder' => null, 'hint' => null, 'value' => null])

@php
    $id = 'f-'.str_replace(['[', ']', '.'], '-', $name);
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $error = $errors->first($key);
    $control = 'mt-2 block w-full rounded-sm border bg-white px-4 py-3.5 text-charcoal transition placeholder:text-charcoal/40 focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/20 '
        .($error ? 'border-red-600' : 'border-charcoal/15');
    $current = old($key, $value);
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="text-sm font-semibold text-charcoal">
        {{ $label }} @if ($attributes->has('required'))<span class="text-walnut" aria-hidden="true">*</span>@endif
    </label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="5" placeholder="{{ $placeholder }}"
                  @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                  {{ $attributes->except('class')->merge(['class' => $control]) }}>{{ $current }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                {{ $attributes->except('class')->merge(['class' => $control]) }}>
            @if ($placeholder)<option value="">{{ $placeholder }}</option>@endif
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
               @unless ($type === 'file') value="{{ $current }}" @endunless
               @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
               {{ $attributes->except('class')->merge(['class' => $control]) }}>
    @endif

    @if ($hint && ! $error)<p class="mt-1.5 text-sm text-charcoal-soft">{{ $hint }}</p>@endif
    @if ($error)<p id="{{ $id }}-error" class="mt-1.5 text-sm text-red-700">{{ $error }}</p>@endif
</div>
