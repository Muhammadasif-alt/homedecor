@php $editing = $category->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit category' : 'New category'">
    <x-admin.page-header :title="$editing ? $category->name : 'New project category'" :back="route('admin.project-categories.index')" />

    <x-admin.form :action="$editing ? route('admin.project-categories.update', $category) : route('admin.project-categories.store')" :method="$editing ? 'PUT' : 'POST'" class="max-w-2xl space-y-6">
        <x-admin.card>
            <div class="grid gap-5">
                <x-admin.input name="name" label="Name" :value="$category->name" required />
                <x-admin.input name="slug" label="Slug" :value="$category->slug" hint="Generated from the name when empty." />
                <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$category->sort_order" />
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.project-categories.index')" />
    </x-admin.form>
</x-admin.layout>
