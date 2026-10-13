@props(['href', 'active' => false, 'icon' => 'document', 'badge' => null])

<a href="{{ $href }}" @if ($active) aria-current="page" @endif
   @class([
       'group flex items-center gap-3 rounded px-3 py-2 text-sm transition-colors focus-visible:outline-sand',
       'bg-white/10 font-semibold text-ivory' => $active,
       'text-ivory/75 hover:bg-white/5 hover:text-ivory' => ! $active,
   ])>
    <x-admin.icon :name="$icon" :class="$active ? 'size-5 shrink-0 text-sand' : 'size-5 shrink-0 text-ivory/50 group-hover:text-sand'" />
    <span class="flex-1">{{ $slot }}</span>
    @if ($badge)
        <span class="rounded-full bg-sand px-2 py-0.5 text-xs font-bold text-charcoal" aria-label="{{ $badge }} new">{{ $badge }}</span>
    @endif
</a>
