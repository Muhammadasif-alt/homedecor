<x-admin.layout title="Paste article: photos">
    <x-admin.page-header :title="$post ? 'Replace: '.$post->title : 'New article from pasted text'" :back="route('admin.blog-posts.import', $post ? ['post' => $post->id] : [])"
                         description="Add a photo for each image note, check the details, then publish." />

    <x-admin.form :action="route('admin.blog-posts.import.store')">
        <input type="hidden" name="post_id" value="{{ $post?->id }}">
        <textarea name="text" class="hidden" aria-hidden="true">{{ $text }}</textarea>

        <x-admin.form-grid>
            <x-admin.card title="Photos" :description="count($article->images) ? 'The first image note is the featured photo at the top of the article; the others appear in the article where they were written.' : 'The pasted text has no [IMAGE: …] notes. You can still choose a featured photo after publishing.'">
                <div class="grid gap-6">
                    @forelse ($article->images as $i => $description)
                        <div class="grid gap-3 rounded border border-charcoal/10 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-walnut">{{ $i === 0 ? 'Featured photo' : 'Photo '.$i }}</p>
                            <p class="text-sm text-charcoal-soft">{{ $description }}</p>
                            <x-admin.media-picker :name="'slots['.$i.'][media_id]'" :value="$prefill[$i]['media']->id ?? ($i === 0 ? $defaults['featured_media_id'] : null)" />
                            @if ($i > 0)
                                <x-admin.input :name="'slots['.$i.'][caption]'" label="Caption (optional)" :value="$prefill[$i]['caption'] ?? null" />
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-charcoal-soft">No image notes found.</p>
                    @endforelse
                </div>
            </x-admin.card>

            <x-admin.card title="Article details">
                <div class="grid gap-5">
                    <x-admin.input name="title" label="Title" :value="$defaults['title']" required />
                    <x-admin.input name="slug" label="Slug" :value="$defaults['slug']" hint="The web address. Keep it the same when replacing a live article." />
                    <x-admin.textarea name="excerpt" label="Excerpt" :value="$defaults['excerpt']" rows="3" :counter="500" required />
                    <x-admin.input name="meta_title" label="SEO title (optional)" :value="$defaults['meta_title']" hint="Leave empty to use the article title." />
                    <x-admin.textarea name="meta_description" label="SEO description" :value="$defaults['meta_description']" rows="2" :counter="160" />
                </div>
            </x-admin.card>

            <x-admin.card title="Preview" description="How the article will read on the site, with the photos it has so far.">
                <div class="prose prose-sm max-w-none">{!! $html !!}</div>
            </x-admin.card>

            <x-slot:aside>
                <x-admin.card title="Publishing">
                    <x-admin.select name="status" label="Status" :options="\App\Http\Requests\Admin\BlogPostRequest::STATUSES" :value="$defaults['status']" />
                </x-admin.card>
                <x-admin.card title="Organisation">
                    <div class="space-y-5">
                        <x-admin.multi-select name="categories" label="Categories" :options="$categoryOptions" :selected="$defaults['categories']" empty="Create a blog category first." />
                        <x-admin.select name="service_id" label="Related service" :options="$serviceOptions" :value="$defaults['service_id']" placeholder="— None —" />
                    </div>
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.blog-posts.index')" :label="$post ? 'Replace article' : 'Publish article'" />
    </x-admin.form>
</x-admin.layout>
