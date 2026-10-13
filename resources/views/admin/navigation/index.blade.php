<x-admin.layout title="Navigation">
    <x-admin.page-header title="Navigation" description="Links in the header and footer menus." />

    @foreach (\App\Models\NavigationItem::MENUS as $menu => $menuLabel)
        @php $items = $menus->get($menu, collect()); @endphp
        <x-admin.card :title="$menuLabel" :padding="false">
            <x-slot:actions>
                <x-admin.button size="sm" variant="secondary" icon="plus" :href="route('admin.navigation.create', ['menu' => $menu])">Add link</x-admin.button>
            </x-slot:actions>
            <ol class="divide-y divide-charcoal/5">
                @forelse ($items as $item)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3 sm:px-6">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold">
                                {{ $item->label }}
                                @if ($item->is_button)<x-admin.status-badge status="featured" label="Button" class="ml-1" />@endif
                            </p>
                            <p class="truncate text-xs text-charcoal-soft">{{ $item->url }}</p>
                        </div>
                        <x-admin.status-badge :status="$item->is_enabled ? 'enabled' : 'hidden'" :label="$item->is_enabled ? 'Shown' : 'Hidden'" />
                        <div class="flex items-center gap-1.5">
                            @foreach (['up' => 'chevron-up', 'down' => 'chevron-down'] as $direction => $icon)
                                <form method="POST" action="{{ route('admin.navigation.move', $item) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="direction" value="{{ $direction }}">
                                    <button type="submit" title="Move {{ $direction }}" @disabled(($direction === 'up' && $loop->parent->first) || ($direction === 'down' && $loop->parent->last))
                                            class="rounded border border-charcoal/15 bg-white p-1.5 text-charcoal-soft hover:border-walnut hover:text-walnut disabled:opacity-30">
                                        <x-admin.icon :name="$icon" class="size-4" /><span class="sr-only">Move {{ $direction }}</span>
                                    </button>
                                </form>
                            @endforeach
                            <x-admin.icon-link :href="route('admin.navigation.edit', $item)" label="Edit {{ $item->label }}" />
                            <x-admin.delete-button :action="route('admin.navigation.destroy', $item)" label="Delete {{ $item->label }}" confirm="Delete this menu link?" />
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-6 text-center text-sm text-charcoal-soft">No links in this menu.</li>
                @endforelse
            </ol>
        </x-admin.card>
    @endforeach
</x-admin.layout>
