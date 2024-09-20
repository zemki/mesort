@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="block mt-2 text-center lg:text-left">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Project') }} {{ $study->name }}</h1>
            <p class="mt-2 text-sm font-medium text-gray-500">{{ $study->description }}</p>
        </div>

        <div class="space-y-6 mt-8">
            <section aria-labelledby="interview-list">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 id="interview-list"
                            class="text-lg font-medium leading-6 text-gray-900">{{ __('Interview list') }}</h2>
                    </div>
                    <div class="px-4 py-5 border-t border-gray-200 sm:px-6">
                        <interview-list :interviews="{{ $interviews }}"></interview-list>
                    </div>
                    @if(count($interviews) > 0)
                        <div class="flex justify-center py-4 bg-gray-50 sm:rounded-b-lg">
                            <a href="{{ url('export/'.$study->id.'/study') }}"
                               class="flex items-center px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-6 h-6 mr-2">
                                    <path
                                        d="M192 312C192 298.8 202.8 288 216 288H384V160H256c-17.67 0-32-14.33-32-32L224 0H48C21.49 0 0 21.49 0 48v416C0 490.5 21.49 512 48 512h288c26.51 0 48-21.49 48-48v-128H216C202.8 336 192 325.3 192 312zM256 0v128h128L256 0zM568.1 295l-80-80c-9.375-9.375-24.56-9.375-33.94 0s-9.375 24.56 0 33.94L494.1 288H384v48h110.1l-39.03 39.03C450.3 379.7 448 385.8 448 392s2.344 12.28 7.031 16.97c9.375 9.375 24.56 9.375 33.94 0l80-80C578.3 319.6 578.3 304.4 568.1 295z"/>
                                </svg>
                                <span>{{ __('Export all the interviews') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </section>

            <section>
                <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        <div class="px-4 py-5 sm:px-6">
                            <h2 id="notes-title"
                                class="text-lg font-medium text-gray-900">{{ __('Public Url List') }}</h2>
                        </div>
                        <div class="px-4 py-6 sm:px-6">
                            <url-list :urls="{{ $publicInterviews }}"></url-list>
                        </div>
                    </div>
                </div>
            </section>

            @if(auth()->user()->is($study->creator()))
                <section>
                    <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 class="text-lg font-medium text-gray-900">{{ __('Study Invites') }}</h2>
                            </div>
                            <div class="px-4 py-6 sm:px-6">
                                <study-invites :invitedlist="{{ $invites }}" :study="{{ $study->id }}"></study-invites>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
