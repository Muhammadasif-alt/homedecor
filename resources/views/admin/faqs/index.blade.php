<x-admin.layout title="FAQs">
    <x-admin.page-header title="FAQs" description="General questions and questions shown on individual service pages."
                         :action="route('admin.faqs.create', is_numeric(request('service')) ? ['service_id' => request('service')] : [])" action-label="Add FAQ" />

    <x-admin.filters :action="route('admin.faqs.index')" placeholder="Search questions…">
        <x-admin.filter-select name="service" :options="$serviceFilter" placeholder="All services" />
        <x-admin.filter-select name="category" :options="\App\Models\Faq::CATEGORIES" placeholder="All categories" />
    </x-admin.filters>

    <x-admin.table :headers="['Question', 'Service', 'Category', 'Home', 'Status', '']" :paginator="$faqs" empty="No FAQs found.">
        @foreach ($faqs as $faq)
            <tr>
                <td class="max-w-md"><a href="{{ route('admin.faqs.edit', $faq) }}" class="font-semibold hover:text-walnut">{{ $faq->question }}</a></td>
                <td class="text-charcoal-soft">{{ $faq->service?->name ?? 'General' }}</td>
                <td class="text-charcoal-soft">{{ \App\Models\Faq::CATEGORIES[$faq->category] ?? $faq->category }}</td>
                <td>@if ($faq->show_on_home)<x-admin.icon name="check" class="size-4 text-emerald-700" /><span class="sr-only">Shown on home</span>@endif</td>
                <td><x-admin.status-badge :status="$faq->is_published ? 'published' : 'draft'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.faqs.edit', $faq)" label="Edit FAQ" />
                    <x-admin.delete-button :action="route('admin.faqs.destroy', $faq)" label="Delete FAQ" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
