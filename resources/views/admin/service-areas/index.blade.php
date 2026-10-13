<x-admin.layout title="Service areas">
    <x-admin.page-header title="Service areas" description="Neighbourhoods and cities you work in."
                         :action="route('admin.service-areas.create')" action-label="Add area" />

    @include('admin.service-areas._note')

    <x-admin.table :headers="['Area', 'Own page', 'Order', 'Status', '']" :paginator="$areas" empty="No service areas yet.">
        @foreach ($areas as $area)
            <tr>
                <td>
                    <a href="{{ route('admin.service-areas.edit', $area) }}" class="font-semibold hover:text-walnut">{{ $area->name }}</a>
                    @if ($area->summary)<p class="max-w-md truncate text-xs text-charcoal-soft">{{ $area->summary }}</p>@endif
                </td>
                <td>{{ $area->has_page ? '/service-areas/'.$area->slug : '—' }}</td>
                <td>{{ $area->sort_order }}</td>
                <td><x-admin.status-badge :status="$area->is_published ? 'published' : 'draft'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.service-areas.edit', $area)" label="Edit {{ $area->name }}" />
                    <x-admin.delete-button :action="route('admin.service-areas.destroy', $area)" label="Delete {{ $area->name }}" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
