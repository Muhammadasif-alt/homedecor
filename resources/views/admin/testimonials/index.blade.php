<x-admin.layout title="Testimonials">
    <x-admin.page-header title="Testimonials" description="Customer reviews. Only approved testimonials appear on the website."
                         :action="route('admin.testimonials.create')" action-label="Add testimonial" />

    @include('admin.testimonials._genuine-note')

    <x-admin.filters :action="route('admin.testimonials.index')" placeholder="Search name or text…">
        <x-admin.filter-select name="approved" :options="['1' => 'Approved', '0' => 'Not approved']" placeholder="Any status" />
    </x-admin.filters>

    <x-admin.table :headers="['Customer', 'Review', 'Rating', 'Status', '']" :paginator="$testimonials" empty="No testimonials yet.">
        @foreach ($testimonials as $testimonial)
            <tr>
                <td>
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="flex items-center gap-3 hover:text-walnut">
                        <x-admin.thumb :media="$testimonial->photo" size="size-10" />
                        <span>
                            <span class="block font-semibold">{{ $testimonial->customer_name }}</span>
                            <span class="block text-xs text-charcoal-soft">{{ $testimonial->service?->name }}</span>
                        </span>
                    </a>
                </td>
                <td class="max-w-md text-charcoal-soft">{{ \Illuminate\Support\Str::limit($testimonial->content, 110) }}</td>
                <td class="whitespace-nowrap">{{ $testimonial->rating ? str_repeat('★', $testimonial->rating).str_repeat('☆', 5 - $testimonial->rating) : '—' }}</td>
                <td><x-admin.status-badge :status="$testimonial->is_approved ? 'approved' : 'pending'" /></td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.testimonials.edit', $testimonial)" label="Edit testimonial" />
                    <x-admin.delete-button :action="route('admin.testimonials.destroy', $testimonial)" label="Delete testimonial" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
