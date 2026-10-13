@props(['name', 'tone' => 'dark'])

<a href="{{ route('home') }}" {{ $attributes->class(['group inline-flex items-center gap-3']) }} aria-label="{{ $name }} — home">
    <span @class([
        'grid size-10 place-items-center rounded-full border font-serif text-lg transition group-hover:bg-walnut group-hover:text-white group-hover:border-walnut',
        'border-charcoal/30 text-charcoal' => $tone === 'dark',
        'border-white/40 text-white' => $tone === 'light',
    ])>{{ mb_substr($name, 0, 1) }}</span>
    <span @class(['font-serif text-2xl leading-none tracking-tight', 'text-charcoal' => $tone === 'dark', 'text-white' => $tone === 'light'])>{{ $name }}</span>
</a>
