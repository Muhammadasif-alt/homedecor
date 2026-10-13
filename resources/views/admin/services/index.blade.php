<x-admin.layout title="Services">
    <x-admin.page-header title="Services" description="Each service has its own page built from this content."
                         :action="route('admin.services.create')" action-label="New service" />

    <x-admin.filters :action="route('admin.services.index')" placeholder="Search services…">
        <x-admin.filter-select name="status" :options="['published' => 'Published', 'draft' => 'Draft']" placeholder="Any status" />
    </x-admin.filters>

    <x-admin.table :headers="['Service', 'Order', 'Featured', 'Status', '']" :paginator="$services" empty="No services yet.">
        @foreach ($services as $service)
            <tr>
                <td>
                    <a href="{{ route('admin.services.edit', $service) }}" class="flex items-center gap-3 hover:text-walnut">
                        <x-admin.thumb :media="$service->heroMedia" />
                        <span>
                            <span class="block font-semibold">{{ $service->name }}</span>
                            <span class="block text-xs text-charcoal-soft">/services/{{ $service->slug }}</span>
                        </span>
                    </a>
                </td>
                <td>{{ $service->sort_order }}</td>
                <td>@if ($service->is_featured)<x-admin.status-badge status="featured" />@endif</td>
                <td><x-admin.status-badge :status="$service->is_published ? 'published' : 'draft'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.services.edit', $service)" label="Edit {{ $service->name }}" />
                    <x-admin.delete-button :action="route('admin.services.destroy', $service)" label="Delete {{ $service->name }}"
                                           confirm="Move this service to the trash? Its page will be removed from the website." />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
