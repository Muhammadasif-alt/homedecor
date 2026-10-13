{{-- Draft / Scheduled / Published badge for a post. --}}
@if ($post->status !== 'published')
    <x-admin.status-badge status="draft" />
@elseif ($post->published_at?->isFuture())
    <x-admin.status-badge status="scheduled" title="Goes live {{ $post->published_at->format('j M Y, H:i') }}" />
@else
    <x-admin.status-badge status="published" />
@endif
