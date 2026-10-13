@props(['href', 'icon' => 'pencil', 'label'])

<a href="{{ $href }}" title="{{ $label }}"
   {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded border border-charcoal/15 bg-white p-1.5 text-charcoal-soft hover:border-walnut hover:text-walnut focus-visible:outline-walnut']) }}>
    <x-admin.icon :name="$icon" class="size-4" />
    <span class="sr-only">{{ $label }}</span>
</a>
