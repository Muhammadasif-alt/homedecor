<x-admin.layout title="Blog categories">
    <x-admin.page-header title="Blog categories" description="Group articles by topic. Each category has its own archive page."
                         :action="route('admin.blog-categories.create')" action-label="New category" />

    <x-admin.table :headers="['Name', 'Slug', 'Articles', 'Order', '']" :paginator="$categories" empty="No categories yet.">
        @foreach ($categories as $category)
            <tr>
                <td><a href="{{ route('admin.blog-categories.edit', $category) }}" class="font-semibold hover:text-walnut">{{ $category->name }}</a></td>
                <td class="text-charcoal-soft">{{ $category->slug }}</td>
                <td>{{ $category->posts_count }}</td>
                <td>{{ $category->sort_order }}</td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.blog-categories.edit', $category)" label="Edit {{ $category->name }}" />
                    <x-admin.delete-button :action="route('admin.blog-categories.destroy', $category)" label="Delete {{ $category->name }}"
                                           confirm="Delete this category? Articles stay, but lose this category." />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
