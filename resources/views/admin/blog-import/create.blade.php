<x-admin.layout title="Paste article">
    <x-admin.page-header title="Paste article" :back="route('admin.blog-posts.index')"
                         description="Copy a whole article from WordPress, Google Docs or an AI writing tool and paste it below: its headings and photos come with it. Or type it the way briefs are written: # for the title, ## for sections, [IMAGE: …] where a photo goes." />

    <x-admin.form :action="route('admin.blog-posts.import.preview')" x-data="articlePaste()" x-on:submit="guard($event)">
        <x-admin.card title="Article text">
            <div class="grid gap-5">
                <x-admin.select name="post_id" label="Publish as" :options="$postOptions" :value="$post?->id"
                                placeholder="— A new article —"
                                hint="Choose an existing article to replace its text and photos, or leave as a new article." />
                <x-admin.textarea name="text" label="Paste the article" rows="22" required x-on:paste="paste($event)"
                                  hint="Pasted photos are uploaded to the media library and placed where they were. Headings, lists, bold text and FAQ questions are converted automatically." />

                <p x-show="notice" x-text="notice" x-cloak role="status" class="rounded bg-walnut/5 px-4 py-3 text-sm text-charcoal"></p>

                <ul x-show="images.length" x-cloak class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6" aria-label="Pasted photos">
                    <template x-for="(image, index) in images" :key="index">
                        <li class="grid gap-1 rounded border border-charcoal/10 p-2 text-xs">
                            <div class="flex aspect-[4/3] items-center justify-center overflow-hidden rounded bg-charcoal/5">
                                <img x-show="image.media" :src="image.media?.url" :alt="image.alt" class="h-full w-full object-cover">
                                <span x-show="image.status === 'uploading'" class="text-charcoal-soft">Uploading…</span>
                                <span x-show="image.status === 'failed'" class="px-2 text-center text-red-700">Not added</span>
                            </div>
                            <span class="truncate font-medium" x-text="index === 0 ? 'Featured: ' + image.alt : image.alt"></span>
                            <span x-show="image.error" x-text="image.error" class="text-red-700"></span>
                            <template x-if="image.media">
                                <span>
                                    <input type="hidden" :name="`pasted[${index}][media_id]`" :value="image.media.id">
                                    <input type="hidden" :name="`pasted[${index}][alt]`" :value="image.alt">
                                    <input type="hidden" :name="`pasted[${index}][caption]`" :value="image.caption">
                                </span>
                            </template>
                        </li>
                    </template>
                </ul>
            </div>
        </x-admin.card>

        <x-admin.form-actions :cancel="route('admin.blog-posts.index')" label="Next: check photos" />
    </x-admin.form>
</x-admin.layout>
