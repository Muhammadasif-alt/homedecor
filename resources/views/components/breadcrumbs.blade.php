@props(['items' => [], 'tone' => 'dark'])

@if (count($items) > 1)
    <nav aria-label="Breadcrumb" {{ $attributes }}>
        <ol @class([
            'flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium uppercase tracking-[0.16em]',
            'text-charcoal-soft' => $tone === 'dark',
            'text-white/80' => $tone === 'light',
        ])>
            @foreach ($items as $crumb)
                <li class="flex items-center gap-2">
                    @if (! $loop->last)
                        <a href="{{ $crumb['url'] }}" class="transition hover:text-walnut {{ $tone === 'light' ? 'hover:text-white' : '' }}">{{ $crumb['name'] }}</a>
                        <x-icon name="chevron-right" size="12" />
                    @else
                        <span aria-current="page" class="{{ $tone === 'light' ? 'text-white' : 'text-charcoal' }}">{{ \Illuminate\Support\Str::limit($crumb['name'], 48) }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
