@php $editing = $category->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit category' : 'New category'">
    <x-admin.page-header :title="$editing ? $category->name : 'New blog category'" :back="route('admin.blog-categories.index')" />

    <x-admin.form :action="$editing ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store')" :method="$editing ? 'PUT' : 'POST'" class="max-w-3xl space-y-6">
        <x-admin.card title="Category">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.input name="name" label="Name" :value="$category->name" required />
                <x-admin.input name="slug" label="Slug" :value="$category->slug" hint="Generated from the name when empty." />
                <x-admin.textarea name="description" label="Description" :value="$category->description" rows="3" :counter="500" class="sm:col-span-2"
                                  hint="Introduces the category archive page." />
                <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$category->sort_order" />
            </div>
        </x-admin.card>
        <x-admin.seo-fields :model="$category" :canonical="false" :og="false" />
        <x-admin.form-actions :cancel="route('admin.blog-categories.index')" />
    </x-admin.form>
</x-admin.layout>
