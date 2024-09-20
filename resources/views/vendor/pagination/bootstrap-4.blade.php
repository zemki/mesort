@if ($paginator->hasPages())
    <nav>
        <ul class="flex list-reset pl-0 rounded list-none">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item opacity-75 list-none" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item list-none">
                    <a class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item opacity-75 list-none" aria-disabled="true"><span class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item text-blue-400 list-none" aria-current="page"><span class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light">{{ $page }}</span></li>
                        @else
                            <li class="page-item list-none"><a class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item list-none">
                    <a class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item list-none opacity-75" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
