@props(['action', 'search' => true, 'placeholder' => 'Search…'])

{{-- GET filter bar for list pages. Extra <x-admin.filter-select>s go in the slot. --}}
<form method="GET" action="{{ $action }}" role="search"
      class="flex flex-col gap-3 rounded-lg border border-charcoal/10 bg-white p-3 shadow-sm sm:flex-row sm:flex-wrap sm:items-center">
    @if ($search)
        <label class="relative min-w-48 flex-1">
            <span class="sr-only">Search</span>
            <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-charcoal/40" />
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}"
                   class="block w-full rounded border border-charcoal/15 bg-white py-2 pl-9 pr-3 text-sm focus:border-walnut focus:outline-none focus:ring-2 focus:ring-walnut/25">
        </label>
    @endif
    {{ $slot }}
    <div class="flex gap-2">
        <x-admin.button variant="secondary">Filter</x-admin.button>
        @if (collect(request()->except('page'))->filter(fn ($v) => filled($v))->isNotEmpty())
            <x-admin.button variant="ghost" :href="$action">Reset</x-admin.button>
        @endif
    </div>
</form>
