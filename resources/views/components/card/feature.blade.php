@props(['icon' => null, 'title', 'number' => null, 'tone' => 'dark'])

<div {{ $attributes->class(['group']) }} data-reveal>
    @if ($number)
        <span class="font-serif text-5xl text-sand">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
    @elseif ($icon)
        <span @class([
            'grid size-14 place-items-center rounded-full transition duration-500',
            'bg-white text-walnut group-hover:bg-walnut group-hover:text-white' => $tone === 'dark',
            'bg-white/10 text-sand group-hover:bg-sand group-hover:text-charcoal' => $tone === 'light',
        ])>
            <x-icon :name="$icon" size="24" />
        </span>
    @endif
    <h3 @class(['mt-6 font-serif text-2xl', 'text-white!' => $tone === 'light'])>{{ $title }}</h3>
    <p @class(['mt-3 leading-relaxed', 'text-charcoal-soft' => $tone === 'dark', 'text-white/75' => $tone === 'light'])>{{ $slot }}</p>
</div>
