{{-- Default paginator: a five-page window, with the first/last page and an ellipsis outside it (1 2 3 4 5 … 10). --}}
@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, min($current - 2, $last - 4));
        $end = min($last, $start + 4);
        $item = 'flex h-11 min-w-11 items-center justify-center border-l border-charcoal/15 text-sm first:border-l-0';
    @endphp
    <nav role="navigation" aria-label="Pagination" class="flex justify-center">
        <ul class="flex overflow-hidden rounded-lg border border-charcoal/15 bg-white text-charcoal">
            <li class="{{ $item }} {{ $paginator->onFirstPage() ? 'text-charcoal/30' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="flex h-full w-full items-center justify-center px-3" aria-hidden="true">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page" class="flex h-full w-full items-center justify-center px-3 hover:bg-ivory">&lsaquo;</a>
                @endif
            </li>

            @if ($start > 1)
                <li class="{{ $item }}"><a href="{{ $paginator->url(1) }}" class="flex h-full w-full items-center justify-center px-3 hover:bg-ivory">1</a></li>
                @if ($start > 2)
                    <li class="{{ $item }} px-3 text-charcoal/50" aria-hidden="true">&hellip;</li>
                @endif
            @endif

            @foreach (range($start, $end) as $page)
                @if ($page === $current)
                    <li class="{{ $item }} bg-ivory-dark px-3 font-medium" aria-current="page">{{ $page }}</li>
                @else
                    <li class="{{ $item }}"><a href="{{ $paginator->url($page) }}" class="flex h-full w-full items-center justify-center px-3 hover:bg-ivory">{{ $page }}</a></li>
                @endif
            @endforeach

            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="{{ $item }} px-3 text-charcoal/50" aria-hidden="true">&hellip;</li>
                @endif
                <li class="{{ $item }}"><a href="{{ $paginator->url($last) }}" class="flex h-full w-full items-center justify-center px-3 hover:bg-ivory">{{ $last }}</a></li>
            @endif

            <li class="{{ $item }} {{ $paginator->hasMorePages() ? '' : 'text-charcoal/30' }}">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page" class="flex h-full w-full items-center justify-center px-3 hover:bg-ivory">&rsaquo;</a>
                @else
                    <span class="flex h-full w-full items-center justify-center px-3" aria-hidden="true">&rsaquo;</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
