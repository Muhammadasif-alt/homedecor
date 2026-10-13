@props(['action', 'label' => 'Delete', 'confirm' => 'Delete this item? This cannot be undone.', 'iconOnly' => true])

{{-- DELETE form with a confirmation prompt. --}}
<form method="POST" action="{{ $action }}" class="inline-flex"
      x-data x-on:submit="if (! confirm({{ \Illuminate\Support\Js::from($confirm) }})) $event.preventDefault()">
    @csrf
    @method('DELETE')
    @if ($iconOnly)
        <button type="submit" title="{{ $label }}"
                {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded border border-red-200 bg-white p-1.5 text-red-700 hover:bg-red-50 focus-visible:outline-red-600']) }}>
            <x-admin.icon name="trash" class="size-4" />
            <span class="sr-only">{{ $label }}</span>
        </button>
    @else
        <x-admin.button variant="danger" icon="trash" {{ $attributes }}>{{ $label }}</x-admin.button>
    @endif
</form>
