{{-- Right-aligned action cell for table rows. --}}
<td {{ $attributes->merge(['class' => 'whitespace-nowrap']) }}>
    <div class="flex items-center justify-end gap-1.5">{{ $slot }}</div>
</td>
