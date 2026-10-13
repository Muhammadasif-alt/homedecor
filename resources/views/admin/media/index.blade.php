<x-admin.layout title="Media library">
    <x-admin.page-header title="Media library" description="Uploads are converted to responsive WebP + JPEG sizes automatically." />

    <x-admin.card title="Upload images" description="JPG, JFIF, PNG or WebP · up to 10 MB each · up to 20 at a time.">
        <x-admin.form :action="route('admin.media.store')" :files="true" class="grid gap-4 sm:grid-cols-[1fr_14rem_auto] sm:items-end">
            <x-admin.field label="Files" for="upload-files" name="files">
                <input id="upload-files" type="file" name="files[]" multiple required accept=".jpg,.jpeg,.jfif,.png,.webp,image/jpeg,image/png,image/webp"
                       class="block w-full rounded border border-charcoal/15 bg-white text-sm file:mr-3 file:border-0 file:bg-ivory-dark file:px-4 file:py-2 file:text-sm file:font-semibold hover:file:bg-sand-light">
                @error('files.*')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
            </x-admin.field>
            <x-admin.select name="category" label="Category" :options="\App\Models\Media::CATEGORIES" placeholder="— None —" />
            <x-admin.button variant="accent" icon="upload">Upload</x-admin.button>
        </x-admin.form>
    </x-admin.card>

    <x-admin.filters :action="route('admin.media.index')" placeholder="Search title, alt text or file name…">
        <x-admin.filter-select name="category" :options="\App\Models\Media::CATEGORIES" placeholder="All categories" />
    </x-admin.filters>

    @if ($media->isEmpty())
        <p class="rounded-lg border border-dashed border-charcoal/20 bg-white px-6 py-16 text-center text-sm text-charcoal-soft">No images found.</p>
    @else
        <ul class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
            @foreach ($media as $item)
                <li class="group overflow-hidden rounded-lg border border-charcoal/10 bg-white shadow-sm">
                    <a href="{{ route('admin.media.edit', $item) }}" class="block focus-visible:outline-walnut">
                        <img src="{{ $item->url(480) }}" alt="{{ $item->alt }}" loading="lazy" class="aspect-square w-full object-cover transition group-hover:opacity-90">
                        <div class="space-y-0.5 px-3 py-2">
                            <p class="truncate text-xs font-semibold">{{ $item->title ?: $item->original_name }}</p>
                            <p class="flex items-center justify-between text-[0.7rem] text-charcoal-soft">
                                <span>{{ $item->width }}×{{ $item->height }}</span>
                                @unless ($item->alt)<span class="font-semibold text-amber-700" title="Missing alt text">No alt</span>@endunless
                            </p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
        {{ $media->links() }}
    @endif
</x-admin.layout>
