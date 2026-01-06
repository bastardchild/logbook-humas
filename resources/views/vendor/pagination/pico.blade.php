@if ($paginator->hasPages())
<nav class="pagination" aria-label="Pagination">
    <ul>

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li><span aria-disabled="true">←</span></li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">←</a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)

            {{-- Dots --}}
            @if (is_string($element))
                <li><span>{{ $element }}</span></li>
            @endif

            {{-- Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><strong>{{ $page }}</strong></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">→</a>
            </li>
        @else
            <li><span aria-disabled="true">→</span></li>
        @endif

    </ul>
</nav>
@endif
