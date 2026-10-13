@props(['name', 'label' => null, 'items' => [], 'fields' => ['title' => 'Title', 'text' => 'Text'], 'addLabel' => 'Add item', 'hint' => null, 'itemLabel' => 'Item'])

{{--
    Alpine list editor for JSON columns, e.g. included / benefits / buttons / items.
    :fields = ['title' => 'Title', 'text' => ['label' => 'Text', 'type' => 'textarea'],
               'style' => ['label' => 'Style', 'type' => 'select', 'options' => [...]],
               'media_id' => ['label' => 'Image', 'type' => 'media']]
    Submits name[i][field].
--}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $uid = 'r-'.str_replace('.', '-', $key);
    $fields = collect($fields)->map(fn ($f) => is_array($f) ? $f + ['type' => 'text'] : ['label' => $f, 'type' => 'text']);
    $mediaFields = $fields->filter(fn ($f) => $f['type'] === 'media')->keys();

    $rows = collect(old($key, $items) ?: [])->filter(fn ($row) => is_array($row))->values();
    $media = \App\Http\Controllers\Admin\Support\MediaPresenter::lookup(
        $mediaFields->flatMap(fn ($field) => $rows->pluck($field))
    );
    $initial = $rows->map(function ($row) use ($fields, $mediaFields, $media) {
        $clean = $fields->map(fn ($f, $field) => $row[$field] ?? ($f['type'] === 'select' ? array_key_first($f['options']) : ''))->all();
        foreach ($mediaFields as $field) {
            $clean['_'.$field] = $media->get((int) ($row[$field] ?? 0));
        }

        return $clean;
    });
    $blank = $fields->map(fn ($f) => $f['type'] === 'select' ? array_key_first($f['options']) : '')->all();
    $rowErrors = collect($errors->getMessages())->filter(fn ($m, $k) => str_starts_with($k, $key.'.'))->flatten()->unique();
    $wide = $fields->count() > 2;
@endphp

<fieldset {{ $attributes->merge(['class' => 'space-y-3']) }}
          x-data="repeater({{ \Illuminate\Support\Js::from($initial) }}, {{ \Illuminate\Support\Js::from($blank) }})">
    @if ($label)
        <legend class="mb-1 text-sm font-medium text-charcoal">{{ $label }}</legend>
    @endif
    <input type="hidden" name="{{ $name }}" value="">

    <ol class="space-y-3">
        <template x-for="(row, index) in items" :key="row._key">
            <li class="rounded-lg border border-charcoal/10 bg-ivory/40 p-4">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-charcoal-soft">{{ $itemLabel }} <span x-text="index + 1"></span></span>
                    <div class="flex gap-1">
                        <button type="button" class="rounded p-1 hover:bg-white disabled:opacity-30" x-on:click="move(index, -1)" x-bind:disabled="index === 0" aria-label="Move up">
                            <x-admin.icon name="chevron-up" class="size-4" />
                        </button>
                        <button type="button" class="rounded p-1 hover:bg-white disabled:opacity-30" x-on:click="move(index, 1)" x-bind:disabled="index === items.length - 1" aria-label="Move down">
                            <x-admin.icon name="chevron-down" class="size-4" />
                        </button>
                        <button type="button" class="rounded p-1 text-red-700 hover:bg-red-50" x-on:click="remove(index)" aria-label="Remove {{ strtolower($itemLabel) }}">
                            <x-admin.icon name="trash" class="size-4" />
                        </button>
                    </div>
                </div>

                <div @class(['grid gap-3', 'sm:grid-cols-2' => $wide])>
                    @foreach ($fields as $field => $config)
                        @php $fieldName = "`{$name}[\${index}][{$field}]`"; $fieldId = "`{$uid}-\${index}-{$field}`"; @endphp
                        <div @class(['space-y-1', 'sm:col-span-2' => $wide && in_array($config['type'], ['textarea', 'media'])])>
                            <label x-bind:for="{{ $fieldId }}" class="block text-xs font-medium text-charcoal-soft">{{ $config['label'] }}</label>
                            @switch($config['type'])
                                @case('textarea')
                                    <textarea x-bind:id="{{ $fieldId }}" x-bind:name="{{ $fieldName }}" x-model="row.{{ $field }}" rows="3"
                                              class="block w-full rounded border border-charcoal/15 bg-white px-3 py-2 text-sm focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/25"></textarea>
                                    @break
                                @case('select')
                                    <select x-bind:id="{{ $fieldId }}" x-bind:name="{{ $fieldName }}" x-model="row.{{ $field }}"
                                            class="block w-full rounded border border-charcoal/15 bg-white px-3 py-2 text-sm focus:border-walnut focus:outline-none">
                                        @foreach ($config['options'] as $value => $optionLabel)
                                            <option value="{{ $value }}">{{ $optionLabel }}</option>
                                        @endforeach
                                    </select>
                                    @break
                                @case('media')
                                    <div class="flex items-center gap-3">
                                        <input type="hidden" x-bind:id="{{ $fieldId }}" x-bind:name="{{ $fieldName }}" x-bind:value="row.{{ $field }} || ''">
                                        <span class="flex size-16 shrink-0 items-center justify-center overflow-hidden rounded border border-dashed border-charcoal/25 bg-white text-charcoal/30">
                                            <template x-if="row._{{ $field }}"><img x-bind:src="row._{{ $field }}.url" alt="" class="size-full object-cover"></template>
                                            <template x-if="! row._{{ $field }}"><x-admin.icon name="photo" class="size-6" /></template>
                                        </span>
                                        <x-admin.button type="button" variant="secondary" size="sm" x-on:click="pickMedia(row, '{{ $field }}')">Choose</x-admin.button>
                                        <x-admin.button type="button" variant="ghost" size="sm" x-show="row.{{ $field }}" x-on:click="row.{{ $field }} = ''; row._{{ $field }} = null">Remove</x-admin.button>
                                    </div>
                                    @break
                                @default
                                    <input type="text" x-bind:id="{{ $fieldId }}" x-bind:name="{{ $fieldName }}" x-model="row.{{ $field }}"
                                           @isset($config['placeholder']) placeholder="{{ $config['placeholder'] }}" @endisset
                                           class="block w-full rounded border border-charcoal/15 bg-white px-3 py-2 text-sm focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/25">
                            @endswitch
                        </div>
                    @endforeach
                </div>
            </li>
        </template>
    </ol>

    <p x-show="! items.length" class="rounded border border-dashed border-charcoal/20 px-4 py-4 text-center text-sm text-charcoal-soft">None yet.</p>
    <x-admin.button type="button" variant="secondary" size="sm" icon="plus" x-on:click="add()">{{ $addLabel }}</x-admin.button>

    @foreach ($rowErrors as $message)
        <p class="text-sm text-red-700">{{ $message }}</p>
    @endforeach
    @error($key)<p class="text-sm text-red-700">{{ $message }}</p>@enderror
    @if ($hint)<p class="text-xs text-charcoal-soft">{{ $hint }}</p>@endif
</fieldset>
