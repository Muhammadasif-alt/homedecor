@props(['name', 'label' => null, 'value' => null, 'hint' => null, 'required' => false])

{{-- Trix editor bound to a hidden input. The saved HTML is purified on the server. --}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = $attributes->get('id', 'f-'.str_replace('.', '-', $key));
@endphp

<x-admin.field :label="$label" :for="$id.'-editor'" :name="$name" :hint="$hint" :required="$required" {{ $attributes->only('class') }}>
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" value="{{ old($key, $value) }}">
    <trix-editor id="{{ $id }}-editor" input="{{ $id }}" role="textbox" aria-multiline="true"
                 @if ($label) aria-label="{{ $label }}" @endif
                 @class(['prose prose-sm max-w-none', 'border-red-400!' => $errors->has($key)])></trix-editor>
</x-admin.field>
