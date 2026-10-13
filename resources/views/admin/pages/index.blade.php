<x-admin.layout title="Pages">
    <x-admin.page-header title="Pages" description="Every public page and its ordered content sections."
                         :action="route('admin.pages.create')" action-label="New page" />

    <x-admin.filters :action="route('admin.pages.index')" placeholder="Search title or slug…">
        <x-admin.filter-select name="template" :options="\App\Http\Requests\Admin\PageRequest::TEMPLATES" placeholder="All templates" />
    </x-admin.filters>

    <x-admin.table :headers="['Page', 'URL', 'Template', 'Sections', 'Status', '']" :paginator="$pages" empty="No pages found.">
        @foreach ($pages as $page)
            <tr>
                <td><a href="{{ route('admin.pages.edit', $page) }}" class="font-semibold hover:text-walnut">{{ $page->title }}</a></td>
                <td class="text-charcoal-soft">/{{ $page->slug === 'home' ? '' : $page->slug }}</td>
                <td>{{ \App\Http\Requests\Admin\PageRequest::TEMPLATES[$page->template] ?? ucfirst($page->template) }}</td>
                <td>{{ $page->sections_count }}</td>
                <td><x-admin.status-badge :status="$page->is_published ? 'published' : 'draft'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.pages.edit', $page)" label="Edit {{ $page->title }}" />
                    <x-admin.delete-button :action="route('admin.pages.destroy', $page)" label="Delete {{ $page->title }}"
                                           confirm="Move this page to the trash? It will disappear from the website." />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
