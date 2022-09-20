@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link">{{ __('previous') }}</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">{{ __('previous') }}</a>
                </li>
            @endif

            @if ($paginator->currentPage() > 2)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
            @endif
            @if ($paginator->currentPage() > 3)
                <li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-ellipsis"></i></span>
                </li>
            @endif

            @foreach (range(1, $paginator->lastPage()) as $i)
                @if ($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
                    @if ($i == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <a class="page-link">{{ $i }} <span class="visually-hidden">(current)</span></a>
                        </li>
                    @else
                        <li class="page-item"><a class="page-link"
                                href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach

            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-ellipsis"></i></span>
                </li>
            @endif
            @if ($paginator->currentPage() < $paginator->lastPage() - 1)
                <li class="page-item"><a class="page-link"
                        href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">{{ __('next') }}</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link">{{ __('next') }}</a>
                </li>
            @endif
        </ul>
    </nav>
@endif
