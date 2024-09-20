@if ($paginator->hasPages())
<div>
  <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
    @if (!$paginator->onFirstPage())
    <a href="{{ $paginator->previousPageUrl() }}"
      class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
      <span class="sr-only">{{__('Previous')}}</span>
      <!-- Heroicon name: solid/chevron-left -->
      <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        aria-hidden="true">
        <path fill-rule="evenodd"
          d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
          clip-rule="evenodd" />
      </svg>
    </a>
    @endif




    @foreach ($elements as $element)

    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <a href="#" aria-current="page"
      class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 border border-indigo-500 bg-indigo-50">
      {{ $page }} </a>
    @else
    @endif
    @endforeach
    @endif
    @endforeach




    @if ($paginator->hasMorePages())

    <a href="{{ $paginator->nextPageUrl() }}"
      class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
      <span class="sr-only">Next</span>
      <!-- Heroicon name: solid/chevron-right -->
      <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        aria-hidden="true">
        <path fill-rule="evenodd"
          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
          clip-rule="evenodd" />
      </svg>
    </a>
    @endif

    @endif