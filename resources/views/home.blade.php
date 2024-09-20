@extends('layouts.app')

@section('content')
    <div class="flex-grow w-full mx-auto max-w-7xl xl:px-8 lg:flex">
        <div class="flex-1 min-w-0 bg-white xl:flex">
            <!-- Studies List -->
            <div class="bg-white lg:min-w-0 lg:flex-1">

                @if(count($studies) === 0 && count($invited_studies) === 0)


                    <div class="mt-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor"
                             aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{__('No projects')}}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{__('Get started by creating a new project.')}}</p>
                        <div class="mt-6">
                            <a title="{{__('Create a new Project')}}" href="{{url('/studies/new')}}">
                                <button type="button"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{__('New Project')}}
                                </button>
                            </a>
                        </div>
                    </div>
            </div>
            @else

                <studies-list studies="{{$studies->merge($invited_studies)}}" user="{{auth()->user()}}"></studies-list>
            @endif
        </div>

    </div>
    </div>

@endsection

@section('pagespecificscripts')

@endsection
