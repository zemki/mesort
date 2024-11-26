@extends('auth.layouts.app')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <img class="mx-auto h-24 w-auto" src="{{ config('utilities.base64logo') }}" alt="MeSort Logo">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ __('Register to MeSort') }}</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Or
                    <a href="{{ url('login') }}" class="font-medium text-blue-600 hover:text-blue-500"> Login </a>
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email-address" class="sr-only">{{ __('E-Mail Address') }}</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Email address">
                    </div>
                    <div class="pt-2">
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="{{ __('Password') }}">
                    </div>
                    <div class="pt-2">
                        <altcha-widget id="altcha-widget" challengeurl="{{url('/altcha-challenge')}}"></altcha-widget>
                        <input type="hidden" id="altoken" name="altoken">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4 mt-5">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Icon container -->
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ __('Whoops! Something went wrong.') }}
                                </h3>
                                <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('pagespecificscripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let altchaWidget = document.getElementById('altcha-widget');
            let altokenInput = document.getElementById('altoken');

            altchaWidget.addEventListener('statechange', function (ev) {
                if (ev.detail.state === 'verified') {
                    altokenInput.value = ev.detail.payload;
                }
            });
        });

    </script>
@endsection

