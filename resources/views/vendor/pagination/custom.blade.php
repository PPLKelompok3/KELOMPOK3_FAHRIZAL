@if ($paginator->hasPages())
  <div class="d-inline-block">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
      <a href="javascript:void(0);" class="prev disabled">Prev</a>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="prev">Prev</a>
    @endif

    {{-- Page Number Links --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <a href="javascript:void(0);" class="disabled">{{ $element }}</a>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <a href="javascript:void(0);" class="active">{{ $page }}</a>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="next">Next</a>
    @else
      <a href="javascript:void(0);" class="next disabled">Next</a>
    @endif
  </div>
@endif
