@props(['name', 'label' => null, 'items' => [], 'captions' => false, 'hint' => null])

{{--
    Ordered multi-image chooser.
    :items = [['id' => 1, 'caption' => '…'], …] (or plain ids).
    Without captions submits name[] = id; with captions submits name[i][media_id] + name[i][caption].
--}}
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $rows = collect(old($key, $items) ?: [])->map(fn ($row) => is_array($row)
        ? ['id' => (int) ($row['id'] ?? $row['media_id'] ?? 0), 'caption' => (string) ($row['caption'] ?? '')]
        : ['id' => (int) $row, 'caption' => ''])->filter(fn ($row) => $row['id'] > 0)->values();
    $media = \App\Http\Controllers\Admin\Support\MediaPresenter::lookup($rows->pluck('id'));
    $initial = $rows->filter(fn ($row) => $media->has($row['id']))
        ->map(fn ($row) => $media[$row['id']] + ['caption' => $row['caption']])->values();
@endphp

<x-admin.field :label="$label" :name="$name" :hint="$hint" {{ $attributes->only('class') }}>
    <div x-data="mediaGallery({{ \Illuminate\Support\Js::from($initial) }})" class="space-y-3">
        <input type="hidden" name="{{ $name }}" value="">
        <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4" x-show="items.length">
            <template x-for="(item, index) in items" :key="item.id">
                <li class="overflow-hidden rounded border border-charcoal/10 bg-ivory">
                    <img x-bind:src="item.url" x-bind:alt="item.alt" class="aspect-[4/3] w-full object-cover">
                    @if ($captions)
                        <input type="hidden" x-bind:name="`{{ $name }}[${index}][media_id]`" x-bind:value="item.id">
                        <label class="sr-only" x-bind:for="`{{ $key }}-caption-${index}`">Caption</label>
                        <input type="text" x-bind:id="`{{ $key }}-caption-${index}`" x-bind:name="`{{ $name }}[${index}][caption]`"
                               x-model="item.caption" placeholder="Caption (optional)" maxlength="255"
                               class="block w-full border-0 border-t border-charcoal/10 bg-white px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-inset focus:ring-walnut/30">
                    @else
                        <input type="hidden" name="{{ $name }}[]" x-bind:value="item.id">
                    @endif
                    <div class="flex items-center justify-between gap-1 bg-white px-2 py-1">
                        <span class="text-xs text-charcoal-soft" x-text="index + 1"></span>
                        <div class="flex gap-1">
                            <button type="button" class="rounded p-1 hover:bg-ivory disabled:opacity-30" x-on:click="move(index, -1)" x-bind:disabled="index === 0" aria-label="Move earlier">
                                <x-admin.icon name="chevron-up" class="size-4 -rotate-90" />
                            </button>
                            <button type="button" class="rounded p-1 hover:bg-ivory disabled:opacity-30" x-on:click="move(index, 1)" x-bind:disabled="index === items.length - 1" aria-label="Move later">
                                <x-admin.icon name="chevron-down" class="size-4 -rotate-90" />
                            </button>
                            <button type="button" class="rounded p-1 text-red-700 hover:bg-red-50" x-on:click="remove(index)" aria-label="Remove image">
                                <x-admin.icon name="x" class="size-4" />
                            </button>
                        </div>
                    </div>
                </li>
            </template>
        </ul>
        <p x-show="! items.length" class="rounded border border-dashed border-charcoal/20 px-4 py-6 text-center text-sm text-charcoal-soft">No images selected.</p>
        <x-admin.button type="button" variant="secondary" size="sm" icon="plus" x-on:click="add()">Add images</x-admin.button>
    </div>
    @error($key.'.*')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
</x-admin.field>
