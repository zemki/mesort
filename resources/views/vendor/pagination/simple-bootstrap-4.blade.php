@if ($paginator->hasPages())
    <nav>
        <ul class="flex list-reset pl-0 rounded list-none">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item opacity-75" aria-disabled="true">
                    <span class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light">@lang('pagination.previous')</span>
                </li>
            @else
                <li class="page-item">
                    <a class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                </li>
            @else
                <li class="page-item opacity-75" aria-disabled="true">
                    <span class="relative block py-2 px-3 -ml-px leading-normal text-blue bg-white border border-grey no-underline hover:text-blue-darker hover:bg-grey-light">@lang('pagination.next')</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
