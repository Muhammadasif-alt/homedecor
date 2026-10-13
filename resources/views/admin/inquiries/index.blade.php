<x-admin.layout title="Leads">
    <x-admin.page-header title="Leads" description="Contact messages and consultation requests from the website." />

    <x-admin.filters :action="route('admin.inquiries.index')" placeholder="Search name, email, phone or message…">
        <x-admin.filter-select name="type" :options="\App\Http\Controllers\Admin\InquiryController::TYPES" placeholder="All types" />
        <x-admin.filter-select name="status" :options="\App\Models\Inquiry::STATUSES" placeholder="Any status" />
    </x-admin.filters>

    <x-admin.table :headers="['Name', 'Type', 'Service', 'Received', 'Status', '']" :paginator="$inquiries" empty="No leads match these filters.">
        @foreach ($inquiries as $inquiry)
            <tr @class(['bg-sand-light/20' => $inquiry->status === 'new'])>
                <td>
                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="hover:text-walnut">
                        <span class="block font-semibold">{{ $inquiry->name }}</span>
                        <span class="block text-xs text-charcoal-soft">{{ $inquiry->email }}{{ $inquiry->phone ? ' · '.$inquiry->phone : '' }}</span>
                    </a>
                </td>
                <td><x-admin.status-badge :status="$inquiry->type" :label="ucfirst($inquiry->type)" /></td>
                <td class="text-charcoal-soft">{{ $inquiry->service?->name ?? '—' }}</td>
                <td class="whitespace-nowrap text-charcoal-soft" title="{{ $inquiry->created_at->format('j M Y, H:i') }}">{{ $inquiry->created_at->diffForHumans() }}</td>
                <td><x-admin.status-badge :status="$inquiry->status" :label="$inquiry->statusLabel()" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.inquiries.show', $inquiry)" icon="eye" label="Open lead from {{ $inquiry->name }}" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
