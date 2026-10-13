@props(['name', 'label' => null, 'value' => null, 'hint' => null])

{{-- Single image chooser backed by the media library modal. Submits the media id. --}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f-'.str_replace('.', '-', $key);
    $initial = \App\Http\Controllers\Admin\Support\MediaPresenter::find(old($key, $value));
@endphp

<x-admin.field :label="$label" :for="$id" :name="$name" :hint="$hint" {{ $attributes->only('class') }}>
    <div x-data="mediaPicker({{ \Illuminate\Support\Js::from($initial) }})" class="flex items-center gap-4">
        <input type="hidden" id="{{ $id }}" name="{{ $name }}" x-bind:value="media ? media.id : ''">

        <button type="button" x-on:click="pick()"
                class="relative flex size-24 shrink-0 items-center justify-center overflow-hidden rounded border border-dashed border-charcoal/25 bg-ivory text-charcoal/40 hover:border-walnut focus-visible:outline-walnut"
                aria-label="Choose image">
            <template x-if="media">
                <img x-bind:src="media.url" x-bind:alt="media.alt" class="size-full object-cover">
            </template>
            <template x-if="! media">
                <x-admin.icon name="photo" class="size-8" />
            </template>
        </button>

        <div class="min-w-0 space-y-2">
            <p class="truncate text-sm text-charcoal" x-text="media ? media.title : 'No image selected'"></p>
            <div class="flex flex-wrap gap-2">
                <x-admin.button type="button" variant="secondary" size="sm" icon="photo" x-on:click="pick()">
                    <span x-text="media ? 'Change' : 'Choose image'"></span>
                </x-admin.button>
                <x-admin.button type="button" variant="ghost" size="sm" x-show="media" x-on:click="clear()">Remove</x-admin.button>
            </div>
        </div>
    </div>
</x-admin.field>
