<x-admin.layout title="Project categories">
    <x-admin.page-header title="Project categories" description="Filters on the portfolio page."
                         :action="route('admin.project-categories.create')" action-label="New category" />

    <x-admin.table :headers="['Name', 'Slug', 'Projects', 'Order', '']" :paginator="$categories" empty="No categories yet.">
        @foreach ($categories as $category)
            <tr>
                <td><a href="{{ route('admin.project-categories.edit', $category) }}" class="font-semibold hover:text-walnut">{{ $category->name }}</a></td>
                <td class="text-charcoal-soft">{{ $category->slug }}</td>
                <td>{{ $category->projects_count }}</td>
                <td>{{ $category->sort_order }}</td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.project-categories.edit', $category)" label="Edit {{ $category->name }}" />
                    <x-admin.delete-button :action="route('admin.project-categories.destroy', $category)" label="Delete {{ $category->name }}"
                                           confirm="Delete this category? Its projects will become uncategorised." />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
