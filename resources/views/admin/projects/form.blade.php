@php
    $editing = $project->exists;
    $images = $editing ? $project->images->map(fn ($i) => ['id' => $i->media_id, 'caption' => $i->caption])->all() : [];
@endphp

<x-admin.layout :title="$editing ? 'Edit project' : 'New project'">
    <x-admin.page-header :title="$editing ? $project->title : 'New project'" :back="route('admin.projects.index')">
        @if ($editing && $project->is_published && Route::has('projects.show'))
            <x-slot:actions>
                <x-admin.button variant="secondary" icon="external" :href="route('projects.show', $project)" target="_blank" rel="noopener">View</x-admin.button>
            </x-slot:actions>
        @endif
    </x-admin.page-header>

    <x-admin.form :action="$editing ? route('admin.projects.update', $project) : route('admin.projects.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Basics">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.input name="title" label="Project title" :value="$project->title" required class="sm:col-span-2" />
                    <x-admin.input name="slug" label="Slug" :value="$project->slug" hint="Generated from the title when empty." />
                    <x-admin.select name="project_category_id" label="Category" :options="$categories" :value="$project->project_category_id" placeholder="— None —" />
                    <x-admin.input name="location" label="Location (optional)" :value="$project->location" hint="Neighbourhood or city — only with the client’s consent." />
                    <x-admin.textarea name="summary" label="Summary" :value="$project->summary" rows="3" :counter="500" required class="sm:col-span-2" />
                </div>
            </x-admin.card>

            @foreach (\App\Http\Requests\Admin\ProjectRequest::RICH_FIELDS as $field => $fieldLabel)
                <x-admin.card :title="$fieldLabel">
                    <x-admin.rich-text :name="$field" :value="$project->{$field}" />
                </x-admin.card>
            @endforeach

            <x-admin.card title="Project images" description="Gallery images with optional captions, in display order.">
                <x-admin.media-gallery name="images" :items="$images" :captions="true" />
            </x-admin.card>

            <x-admin.card title="Services delivered">
                <x-admin.multi-select name="services" :options="$serviceOptions" :selected="$editing ? $project->services->pluck('id')->all() : []" />
            </x-admin.card>

            <x-admin.seo-fields :model="$project" :canonical="false" :og="false" />

            <x-slot:aside>
                <x-admin.card title="Publishing">
                    <div class="space-y-4">
                        <x-admin.checkbox name="is_published" label="Published" :checked="$project->is_published" />
                        <x-admin.checkbox name="is_featured" label="Featured" :checked="$project->is_featured" hint="Shown in featured project sections." />
                        <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$project->sort_order" />
                    </div>
                </x-admin.card>
                <x-admin.card title="Cover image">
                    <x-admin.media-picker name="cover_media_id" :value="$project->cover_media_id" />
                </x-admin.card>
                <x-admin.card title="Before & after" description="Only use a real before/after pair of this project.">
                    <div class="space-y-5">
                        <x-admin.media-picker name="before_media_id" label="Before" :value="$project->before_media_id" />
                        <x-admin.media-picker name="after_media_id" label="After" :value="$project->after_media_id" />
                    </div>
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.projects.index')" :label="$editing ? 'Save project' : 'Create project'" />
    </x-admin.form>
</x-admin.layout>
