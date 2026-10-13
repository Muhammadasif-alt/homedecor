@props(['cancel' => null, 'label' => 'Save changes'])

{{-- Sticky save bar at the bottom of edit forms. --}}
<div class="sticky bottom-0 z-20 flex items-center justify-end gap-3 rounded-lg border border-charcoal/10 bg-white/95 px-4 py-3 shadow-sm backdrop-blur">
    {{ $slot }}
    @if ($cancel)
        <x-admin.button variant="ghost" :href="$cancel">Cancel</x-admin.button>
    @endif
    <x-admin.button icon="check">{{ $label }}</x-admin.button>
</div>
