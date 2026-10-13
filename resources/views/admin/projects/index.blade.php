<x-admin.layout title="Portfolio">
    <x-admin.page-header title="Portfolio" description="Completed projects shown in the gallery. Only publish real work."
                         :action="route('admin.projects.create')" action-label="New project" />

    <x-admin.filters :action="route('admin.projects.index')" placeholder="Search title or location…">
        <x-admin.filter-select name="category" :options="$categories" placeholder="All categories" />
        <x-admin.filter-select name="status" :options="['published' => 'Published', 'draft' => 'Draft']" placeholder="Any status" />
    </x-admin.filters>

    <x-admin.table :headers="['Project', 'Category', 'Location', 'Status', '']" :paginator="$projects" empty="No projects yet.">
        @foreach ($projects as $project)
            <tr>
                <td>
                    <a href="{{ route('admin.projects.edit', $project) }}" class="flex items-center gap-3 hover:text-walnut">
                        <x-admin.thumb :media="$project->cover" />
                        <span class="font-semibold">{{ $project->title }}</span>
                        @if ($project->is_featured)<x-admin.status-badge status="featured" />@endif
                    </a>
                </td>
                <td>{{ $project->category?->name ?? '—' }}</td>
                <td class="text-charcoal-soft">{{ $project->location ?: '—' }}</td>
                <td><x-admin.status-badge :status="$project->is_published ? 'published' : 'draft'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.projects.edit', $project)" label="Edit {{ $project->title }}" />
                    <x-admin.delete-button :action="route('admin.projects.destroy', $project)" label="Delete {{ $project->title }}"
                                           confirm="Move this project to the trash?" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
