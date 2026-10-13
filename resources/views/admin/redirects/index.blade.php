<x-admin.layout title="Redirects">
    <x-admin.page-header title="Redirects" description="Send old or changed URLs to their new location so visitors and search engines don’t hit a 404."
                         :action="route('admin.redirects.create')" action-label="Add redirect" />

    <x-admin.filters :action="route('admin.redirects.index')" placeholder="Search paths…" />

    <x-admin.table :headers="['From', 'To', 'Type', 'Hits', '']" :paginator="$redirects" empty="No redirects yet.">
        @foreach ($redirects as $redirect)
            <tr>
                <td class="font-mono text-xs"><a href="{{ route('admin.redirects.edit', $redirect) }}" class="hover:text-walnut">{{ $redirect->from_path }}</a></td>
                <td class="max-w-xs truncate font-mono text-xs text-charcoal-soft">{{ $redirect->to_url }}</td>
                <td><x-admin.status-badge :status="$redirect->status_code === 301 ? 'published' : 'scheduled'" :label="$redirect->status_code === 301 ? '301 Permanent' : '302 Temporary'" /></td>
                <td>{{ number_format($redirect->hits) }}</td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.redirects.edit', $redirect)" label="Edit redirect" />
                    <x-admin.delete-button :action="route('admin.redirects.destroy', $redirect)" label="Delete redirect" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
