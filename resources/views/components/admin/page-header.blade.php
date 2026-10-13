@props(['title', 'description' => null, 'action' => null, 'actionLabel' => null, 'back' => null])

{{-- Page title row. Pass :action (URL) + action-label for a primary button, or use the "actions" slot. --}}
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" class="mb-2 inline-flex items-center gap-1 text-sm text-charcoal-soft hover:text-walnut">&larr; Back</a>
        @endif
        <h1 class="font-serif text-3xl font-medium text-charcoal sm:text-4xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-charcoal-soft">{{ $description }}</p>
        @endif
    </div>
    <div class="flex shrink-0 flex-wrap items-center gap-2">
        {{ $actions ?? '' }}
        @if ($action)
            <x-admin.button :href="$action" icon="plus">{{ $actionLabel ?? 'Add new' }}</x-admin.button>
        @endif
    </div>
</div>
