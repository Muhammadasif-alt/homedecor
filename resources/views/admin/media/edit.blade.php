@php $used = array_sum($usage); @endphp

<x-admin.layout title="Edit image">
    <x-admin.page-header :title="$media->title ?: 'Image'" :back="route('admin.media.index')"
                         :description="($media->original_name ? $media->original_name.' · ' : '').$media->width.'×'.$media->height.' px · '.number_format($media->size / 1024).' KB'" />

    <div class="grid items-start gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-admin.card :padding="false">
                <img src="{{ $media->url(1600) }}" alt="{{ $media->alt }}" class="max-h-[32rem] w-full rounded-t-lg bg-ivory-dark object-contain">
                <p class="px-5 py-3 text-xs text-charcoal-soft">Sizes: {{ implode(', ', $media->widths()) }} px (WebP + JPEG)</p>
            </x-admin.card>

            <x-admin.form :action="route('admin.media.update', $media)" method="PUT">
                <x-admin.card title="Details">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.input name="title" label="Title" :value="$media->title" />
                        <x-admin.select name="category" label="Category" :options="\App\Models\Media::CATEGORIES" :value="$media->category" placeholder="— None —" />
                        <x-admin.input name="alt" label="Alt text" :value="$media->alt" class="sm:col-span-2"
                                       hint="Describe what the image shows for screen readers and search engines, e.g. “Warm neutral living room with linen sofa”." />
                        <x-admin.input name="caption" label="Caption" :value="$media->caption" class="sm:col-span-2" />
                    </div>
                    <x-slot:footer>
                        <x-admin.button icon="check">Save details</x-admin.button>
                    </x-slot:footer>
                </x-admin.card>
            </x-admin.form>
        </div>

        <aside class="space-y-6">
            <x-admin.card title="Where it’s used">
                @if ($usage)
                    <ul class="space-y-1 text-sm">
                        @foreach ($usage as $label => $count)
                            <li class="flex justify-between"><span>{{ $label }}</span><span class="text-charcoal-soft">{{ $count }}</span></li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-charcoal-soft">Not used by any page, service, project, article or testimonial (section image lists are not checked).</p>
                @endif
            </x-admin.card>

            <x-admin.card title="Replace file" description="Upload a new version. Every place using this image updates automatically.">
                <x-admin.form :action="route('admin.media.replace', $media)" :files="true" class="space-y-3">
                    <x-admin.field for="replace-file" name="file">
                        <input id="replace-file" type="file" name="file" required accept=".jpg,.jpeg,.jfif,.png,.webp,image/jpeg,image/png,image/webp"
                               class="block w-full text-sm file:mr-3 file:rounded file:border-0 file:bg-ivory-dark file:px-3 file:py-1.5 file:text-sm file:font-semibold">
                    </x-admin.field>
                    <x-admin.button variant="secondary" icon="upload" size="sm">Replace</x-admin.button>
                </x-admin.form>
            </x-admin.card>

            <x-admin.card title="Delete">
                @if ($used)
                    <x-admin.notice tone="warning" class="mb-3">This image is used in {{ $used }} place(s). Deleting it removes it from those pages.</x-admin.notice>
                @endif
                <x-admin.delete-button :action="route('admin.media.destroy', $media)" :icon-only="false" label="Delete image"
                                       :confirm="$used ? 'This image is used in '.$used.' place(s). Delete it anyway?' : 'Delete this image and all its sizes?'" />
            </x-admin.card>
        </aside>
    </div>
</x-admin.layout>
