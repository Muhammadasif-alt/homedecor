<x-admin.layout title="Blog posts">
    <x-admin.page-header title="Blog posts" description="Articles and guides. Future publish dates are scheduled automatically."
                         :action="route('admin.blog-posts.create')" action-label="New article">
        <x-slot:actions>
            <x-admin.button variant="secondary" icon="upload" :href="route('admin.blog-posts.import')">Paste article</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.blog-posts.index')" placeholder="Search titles…">
        <x-admin.filter-select name="status" :options="['published' => 'Published', 'scheduled' => 'Scheduled', 'draft' => 'Draft']" placeholder="Any status" />
        <x-admin.filter-select name="category" :options="$categories" placeholder="All categories" />
    </x-admin.filters>

    <x-admin.table :headers="['Article', 'Categories', 'Publish date', 'Status', '']" :paginator="$posts" empty="No articles yet.">
        @foreach ($posts as $post)
            <tr>
                <td>
                    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="flex items-center gap-3 hover:text-walnut">
                        <x-admin.thumb :media="$post->featuredMedia" />
                        <span>
                            <span class="block font-semibold">{{ $post->title }}</span>
                            <span class="block text-xs text-charcoal-soft">{{ $post->author?->name ?? 'Unknown author' }}</span>
                        </span>
                    </a>
                </td>
                <td class="text-charcoal-soft">{{ $post->categories->pluck('name')->implode(', ') ?: '—' }}</td>
                <td class="whitespace-nowrap text-charcoal-soft">{{ $post->published_at?->format('j M Y, H:i') ?? '—' }}</td>
                <td>@include('admin.blog-posts._status', ['post' => $post])</td>
                <x-admin.row-actions>
                    <x-admin.icon-link :href="route('admin.blog-posts.edit', $post)" label="Edit {{ $post->title }}" />
                    <x-admin.delete-button :action="route('admin.blog-posts.destroy', $post)" label="Delete {{ $post->title }}"
                                           confirm="Move this article to the trash?" />
                </x-admin.row-actions>
            </tr>
        @endforeach
    </x-admin.table>
</x-admin.layout>
