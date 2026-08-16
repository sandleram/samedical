@if ($paginator->hasPages() || $paginator->total() > 0)
<table class="table">
    <tr>
        <td>
            <div class="pagination">
                Exibindo registros do {{ $paginator->firstItem() ?? 0 }} ao {{ $paginator->lastItem() ?? 0 }} - Total de {{ $paginator->total() }}
            </div>
        </td>
        <td>
            <div class="text-right">
                <ul class="pagination pagination-sm" style="margin:0;">
                    @if ($paginator->onFirstPage())
                        <li class="hidden"><span>&lt;&lt;</span></li>
                    @else
                        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;&lt;</a></li>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="disabled"><span>{{ $element }}</span></li>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="active"><a href="{{ $url }}">{{ $page }}</a></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;&gt;</a></li>
                    @else
                        <li class="hidden"><span>&gt;&gt;</span></li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
</table>
@endif
