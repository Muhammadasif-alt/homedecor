{{-- Global media library dialog, driven by Alpine.store('media'). Rendered once in the layout. --}}
<div x-data x-show="$store.media.open" x-cloak
     class="fixed inset-0 z-[70] flex items-end justify-center bg-charcoal/70 p-0 sm:items-center sm:p-6"
     x-on:keydown.escape.window="$store.media.close()" role="dialog" aria-modal="true" aria-labelledby="media-modal-title">
    <div class="flex max-h-[92vh] w-full max-w-5xl flex-col rounded-t-lg bg-white shadow-xl sm:rounded-lg"
         x-on:click.outside="$store.media.close()" x-transition>
        <header class="flex items-center justify-between gap-4 border-b border-charcoal/10 px-5 py-4">
            <h2 id="media-modal-title" class="font-sans text-base font-semibold">
                Media library
                <span class="ml-1 text-sm font-normal text-charcoal-soft" x-show="$store.media.multiple">— select one or more images</span>
            </h2>
            <button type="button" class="rounded p-1 hover:bg-ivory" x-on:click="$store.media.close()" aria-label="Close media library">
                <x-admin.icon name="x" />
            </button>
        </header>

        <div class="flex flex-col gap-3 border-b border-charcoal/10 px-5 py-3 sm:flex-row sm:items-center">
            <label class="relative flex-1">
                <span class="sr-only">Search images</span>
                <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-charcoal/40" />
                <input type="search" placeholder="Search by title, alt text or file name…"
                       x-model="$store.media.search" x-on:input.debounce.350ms="$store.media.fetch(true)"
                       class="block w-full rounded border border-charcoal/15 py-2 pl-9 pr-3 text-sm focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/25">
            </label>
            <label class="sm:w-52">
                <span class="sr-only">Category</span>
                <select x-model="$store.media.category" x-on:change="$store.media.fetch(true)"
                        class="block w-full rounded border border-charcoal/15 px-3 py-2 text-sm focus:border-walnut focus:outline-none">
                    <option value="">All categories</option>
                    @foreach (\App\Models\Media::CATEGORIES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="inline-flex cursor-pointer items-center justify-center gap-1.5 rounded border border-transparent bg-sand px-4 py-2 text-sm font-semibold text-charcoal hover:bg-sand-light focus-within:outline-2 focus-within:outline-walnut">
                <x-admin.icon name="upload" class="size-4" />
                <span x-text="$store.media.uploading ? 'Uploading…' : 'Upload new'"></span>
                <input type="file" multiple accept=".jpg,.jpeg,.jfif,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only"
                       x-on:change="$store.media.upload($event.target.files); $event.target.value = ''" x-bind:disabled="$store.media.uploading">
            </label>
        </div>

        <p x-show="$store.media.error" x-text="$store.media.error" class="mx-5 mt-3 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800" role="alert"></p>

        <div class="flex-1 overflow-y-auto px-5 py-4">
            <ul class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-5">
                <template x-for="item in $store.media.items" :key="item.id">
                    <li>
                        <button type="button" x-on:click="$store.media.toggle(item)" x-on:dblclick="$store.media.multiple || $store.media.confirm()"
                                class="group relative block w-full overflow-hidden rounded ring-1 ring-charcoal/10 focus-visible:outline-walnut"
                                x-bind:class="$store.media.isSelected(item) && 'ring-4 ring-walnut'"
                                x-bind:aria-pressed="$store.media.isSelected(item)">
                            <img x-bind:src="item.url" x-bind:alt="item.alt" loading="lazy" class="aspect-square w-full object-cover transition group-hover:opacity-90">
                            <span class="absolute right-1.5 top-1.5 flex size-6 items-center justify-center rounded-full bg-walnut text-white" x-show="$store.media.isSelected(item)">
                                <x-admin.icon name="check" class="size-4" />
                            </span>
                            <span class="block truncate bg-white px-2 py-1 text-left text-xs text-charcoal-soft" x-text="item.title"></span>
                        </button>
                    </li>
                </template>
            </ul>
            <p x-show="! $store.media.loading && ! $store.media.items.length" class="py-12 text-center text-sm text-charcoal-soft">No images found.</p>
            <p x-show="$store.media.loading" class="py-6 text-center text-sm text-charcoal-soft">Loading…</p>
            <div class="pt-4 text-center" x-show="$store.media.hasMore && ! $store.media.loading">
                <x-admin.button type="button" variant="secondary" size="sm" x-on:click="$store.media.fetch()">Load more</x-admin.button>
            </div>
        </div>

        <footer class="flex items-center justify-between gap-3 border-t border-charcoal/10 bg-ivory/50 px-5 py-3">
            <p class="text-sm text-charcoal-soft"><span x-text="$store.media.selected.length"></span> selected</p>
            <div class="flex gap-2">
                <x-admin.button type="button" variant="ghost" x-on:click="$store.media.close()">Cancel</x-admin.button>
                <x-admin.button type="button" icon="check" x-on:click="$store.media.confirm()" x-bind:disabled="! $store.media.selected.length">Use selected</x-admin.button>
            </div>
        </footer>
    </div>
</div>
