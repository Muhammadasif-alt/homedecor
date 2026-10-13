{{-- Session flash messages + a summary when validation fails. --}}
@php
    $tones = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        'error' => 'border-red-200 bg-red-50 text-red-900',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-900',
    ];
@endphp

@foreach ($tones as $type => $classes)
    @if (session($type))
        <div x-data="{ show: true }" x-show="show" x-transition role="status"
             class="flex items-start gap-3 rounded-lg border px-4 py-3 text-sm {{ $classes }}">
            <x-admin.icon :name="$type === 'success' ? 'check' : 'alert'" class="mt-0.5 size-5 shrink-0" />
            <p class="flex-1">{{ session($type) }}</p>
            <button type="button" x-on:click="show = false" class="opacity-60 hover:opacity-100" aria-label="Dismiss">
                <x-admin.icon name="x" class="size-4" />
            </button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div role="alert" class="flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
        <x-admin.icon name="alert" class="mt-0.5 size-5 shrink-0" />
        <p>Please fix the {{ $errors->count() === 1 ? 'highlighted field' : $errors->count().' highlighted fields' }} below.</p>
    </div>
@endif
