@php $editing = $area->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit service area' : 'Add service area'">
    <x-admin.page-header :title="$editing ? $area->name : 'Add service area'" :back="route('admin.service-areas.index')" />

    @include('admin.service-areas._note')

    <x-admin.form :action="$editing ? route('admin.service-areas.update', $area) : route('admin.service-areas.store')" :method="$editing ? 'PUT' : 'POST'">
        <x-admin.form-grid>
            <x-admin.card title="Area">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-admin.input name="name" label="Area name" :value="$area->name" required />
                    <x-admin.input name="slug" label="Slug" :value="$area->slug" hint="Generated from the name when empty." />
                    <x-admin.textarea name="summary" label="Summary" :value="$area->summary" rows="2" :counter="500" class="sm:col-span-2" />
                </div>
            </x-admin.card>

            <x-admin.card title="Area page content" description="Required when the area has its own page: projects completed here, local considerations, travel times…">
                <x-admin.rich-text name="body" :value="$area->body" />
            </x-admin.card>

            <x-admin.seo-fields :model="$area" :canonical="false" :og="false" />

            <x-slot:aside>
                <x-admin.card title="Publishing">
                    <div class="space-y-4">
                        <x-admin.checkbox name="is_published" label="Published" :checked="$area->is_published" />
                        <x-admin.checkbox name="has_page" label="Has its own page" :checked="$area->has_page"
                                          hint="Only with unique, genuinely useful content." />
                        <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$area->sort_order" />
                    </div>
                </x-admin.card>
            </x-slot:aside>
        </x-admin.form-grid>

        <x-admin.form-actions :cancel="route('admin.service-areas.index')" />
    </x-admin.form>
</x-admin.layout>
