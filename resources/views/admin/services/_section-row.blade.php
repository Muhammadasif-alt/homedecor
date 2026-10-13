{{-- One content section row ($index, $row = [id, heading, body, media_id]). Also used as the clone template. --}}
<li data-row class="rounded-lg border border-charcoal/10 bg-ivory/40 p-4">
    @if (! empty($row['id']))
        <input type="hidden" name="sections[{{ $index }}][id]" value="{{ $row['id'] }}">
    @endif
    <div class="mb-3 flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-charcoal-soft">Content block</span>
        <div class="flex gap-1">
            <button type="button" class="rounded p-1 hover:bg-white" x-on:click="move($el, -1)" aria-label="Move block up"><x-admin.icon name="chevron-up" class="size-4" /></button>
            <button type="button" class="rounded p-1 hover:bg-white" x-on:click="move($el, 1)" aria-label="Move block down"><x-admin.icon name="chevron-down" class="size-4" /></button>
            <button type="button" class="rounded p-1 text-red-700 hover:bg-red-50" x-on:click="remove($el)" aria-label="Remove block"><x-admin.icon name="trash" class="size-4" /></button>
        </div>
    </div>
    <div class="grid gap-4">
        <x-admin.input name="sections[{{ $index }}][heading]" label="Heading" :value="$row['heading'] ?? null" />
        <x-admin.rich-text name="sections[{{ $index }}][body]" label="Text" :value="$row['body'] ?? null" />
        <x-admin.media-picker name="sections[{{ $index }}][media_id]" label="Image (optional)" :value="$row['media_id'] ?? null" />
    </div>
</li>
