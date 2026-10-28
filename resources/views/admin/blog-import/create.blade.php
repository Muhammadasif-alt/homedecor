<x-admin.layout title="Paste article">
    <x-admin.page-header title="Paste article" :back="route('admin.blog-posts.index')"
                         description="Paste a whole article, written the way briefs are: # for the title, ## for sections, [IMAGE: …] where a photo goes. The next step lets you add a photo for each image note before it goes live." />

    <x-admin.form :action="route('admin.blog-posts.import.preview')">
        <x-admin.card title="Article text">
            <div class="grid gap-5">
                <x-admin.select name="post_id" label="Publish as" :options="$postOptions" :value="$post?->id"
                                placeholder="— A new article —"
                                hint="Choose an existing article to replace its text and photos, or leave as a new article." />
                <x-admin.textarea name="text" label="Paste the article" rows="22" required
                                  hint="Headings, lists, bold text and FAQ questions are converted automatically." />
            </div>
        </x-admin.card>

        <x-admin.form-actions :cancel="route('admin.blog-posts.index')" label="Next: add photos" />
    </x-admin.form>
</x-admin.layout>
