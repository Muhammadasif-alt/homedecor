@props(['status', 'label' => null])

{{-- Coloured pill for publish / lead states. Accepts a status string or a boolean. --}}
@php
    $key = is_bool($status) ? ($status ? 'yes' : 'no') : (string) $status;
    $tone = match ($key) {
        'published', 'approved', 'active', 'won', 'enabled', 'yes', 'live' => 'bg-emerald-50 text-emerald-800 ring-emerald-600/20',
        'scheduled', 'contacted', 'proposal', 'consultation' => 'bg-sky-50 text-sky-800 ring-sky-600/20',
        'new', 'featured' => 'bg-sand-light text-walnut-dark ring-walnut/30',
        'lost', 'inactive' => 'bg-red-50 text-red-800 ring-red-600/20',
        default => 'bg-ivory-dark text-charcoal-soft ring-charcoal/15',
    };
    $text = $label ?? \Illuminate\Support\Str::headline($key);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {$tone}"]) }}>{{ $text }}</span>
