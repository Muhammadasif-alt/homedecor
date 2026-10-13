@php $editing = $post->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit article' : 'New article'">
    <x-admin.page-header :title="$editing ? $post->title : 'New article'" :back="route('admin.blog-posts.index')"
                         :description="$editing ? 'By '.($post->author?->name ?? 'unknown author').' · last updated '.$post->updated_at->diffForHumans() : null">
        @if ($editing && $post->isLive() && Route::has('blog.show'))
            <x-slot:actions>
                <x-admin.button variant="secondary" icon="external" :href="route('blog.show', $post)" target="_blank" rel="noopener">View</x-admin.button>
            </x-slot:actions>
        @endif
    </x-admin.page-header>

    <x-admin.form :action="$editing ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Article">
                <div class="grid gap-5">
                    <x-admin.input name="title" label="Title" :value="$post->title" required />
                    <x-admin.input name="slug" label="Slug" :value="$post->slug" hint="Generated from the title when empty. Changing it on a live article breaks old links — add a redirect." />
                    <x-admin.textarea name="excerpt" label="Excerpt" :value="$post->excerpt" rows="3" :counter="500" required hint="Shown on article cards and as a fallback meta description." />
                    <x-admin.rich-text name="body" label="Body" :value="$post->body" required hint="Use “Heading” for section titles. Images are not embedded here — use the featured image." />
                </div>
            </x-admin.card>

            <x-admin.seo-fields :model="$post" :robots="true" />

            <x-slot:aside>
                <x-admin.card title="Publishing">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-charcoal-soft">Current status</span>
                            @include('admin.blog-posts._status', ['post' => $post])
                        </div>
                        <x-admin.select name="status" label="Status" :options="\App\Http\Requests\Admin\BlogPostRequest::STATUSES" :value="$post->status" />
                        <x-admin.input name="published_at" type="datetime-local" label="Publish date" :value="$post->published_at"
                                       hint="Leave empty to publish now. A future date schedules the article." />
                    </div>
                </x-admin.card>
                <x-admin.card title="Featured image">
                    <x-admin.media-picker name="featured_media_id" :value="$post->featured_media_id" />
                </x-admin.card>
                <x-admin.card title="Organisation">
                    <div class="space-y-5">
                        <x-admin.multi-select name="categories" label="Categories" :options="$categoryOptions"
                                              :selected="$editing ? $post->categories->pluck('id')->all() : []" empty="Create a blog category first." />
                        <x-admin.select name="service_id" label="Related service" :options="$serviceOptions" :value="$post->service_id"
                                        placeholder="— None —" hint="Adds a call-to-action linking to this service." />
                    </div>
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.blog-posts.index')" :label="$editing ? 'Save article' : 'Create article'" />
    </x-admin.form>
</x-admin.layout>
