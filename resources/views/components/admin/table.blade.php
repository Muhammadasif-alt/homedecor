@props(['headers' => [], 'paginator' => null, 'empty' => 'Nothing here yet.'])

{{-- Responsive data table inside a card. Rows go in the default slot as <tr> elements. --}}
<div class="overflow-hidden rounded-lg border border-charcoal/10 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-charcoal/10 text-sm">
            <thead class="bg-ivory/60">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" @class([
                            'whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wider text-charcoal-soft first:pl-5 last:pr-5',
                            'text-right' => $loop->last && $loop->count > 1,
                            'text-left' => ! ($loop->last && $loop->count > 1),
                        ])>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-charcoal/5 [&_td]:px-4 [&_td]:py-3 [&_td]:align-middle [&_td:first-child]:pl-5 [&_td:last-child]:pr-5">
                @if (trim($slot) === '')
                    <tr><td colspan="{{ max(count($headers), 1) }}" class="py-12 text-center text-charcoal-soft">{{ $empty }}</td></tr>
                @else
                    {{ $slot }}
                @endif
            </tbody>
        </table>
    </div>
    @if ($paginator && $paginator->hasPages())
        <div class="border-t border-charcoal/10 px-5 py-3">{{ $paginator->links() }}</div>
    @endif
</div>
