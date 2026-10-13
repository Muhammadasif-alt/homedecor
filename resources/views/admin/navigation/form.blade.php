@php $editing = $item->exists; @endphp

<x-admin.layout :title="$editing ? 'Edit menu link' : 'Add menu link'">
    <x-admin.page-header :title="$editing ? 'Edit “'.$item->label.'”' : 'Add menu link'" :back="route('admin.navigation.index')" />

    <x-admin.form :action="$editing ? route('admin.navigation.update', $item) : route('admin.navigation.store')" :method="$editing ? 'PUT' : 'POST'" class="max-w-2xl space-y-6">
        <x-admin.card>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.select name="menu" label="Menu" :options="\App\Models\NavigationItem::MENUS" :value="$item->menu" />
                <x-admin.input name="sort_order" type="number" min="0" label="Display order" :value="$item->sort_order" />
                <x-admin.input name="label" label="Label" :value="$item->label" required />
                <x-admin.input name="url" label="Link" :value="$item->url" required placeholder="/services" hint="A path like /services or a full https:// URL." />
                <x-admin.checkbox name="is_button" label="Show as a button" :checked="$item->is_button" hint="e.g. “Book a consultation” in the header." />
                <x-admin.checkbox name="is_enabled" label="Shown" :checked="$item->is_enabled" />
            </div>
        </x-admin.card>
        <x-admin.form-actions :cancel="route('admin.navigation.index')" />
    </x-admin.form>
</x-admin.layout>
