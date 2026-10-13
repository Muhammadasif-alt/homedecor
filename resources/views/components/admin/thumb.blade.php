@props(['media' => null, 'size' => 'size-12'])

{{-- Small square thumbnail for tables (expects an eager-loaded Media or null). --}}
@if ($media)
    <img src="{{ $media->url(480) }}" alt="" loading="lazy" class="{{ $size }} shrink-0 rounded object-cover ring-1 ring-charcoal/10">
@else
    <span class="{{ $size }} flex shrink-0 items-center justify-center rounded bg-ivory-dark text-charcoal/30">
        <x-admin.icon name="photo" class="size-5" />
    </span>
@endif
