@extends('auth.layouts.app')

@section('content')

    <div class="p-4 mx-2 rounded-md bg-yellow-50 md:hidden" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">

                <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                     fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">{{__('Attention Needed')}}</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>{{__('Mesort is intended to be used on a Tablet or Computer')}}</p>
                </div>
            </div>
        </div>
    </div>
    @if(session()->has('message') || !empty($message))
        <div class="p-4 rounded-md bg-red-50">
            <div class="flex">
                <div class="flex-shrink-0">
                    <!-- Heroicon name: solid/x-circle -->
                    <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>

                </div>
                <div class="ml-3">
                    <div class="mt-2 text-sm text-red-700">
                        {{session()->get('message') ? session()->get('message') : $message}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="flex items-center justify-center min-h-full px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <img class="w-auto h-24 mx-auto" src="{{config('utilities.base64logo')}}" alt="Mesort Logo">
                <h1 class="mt-6 text-3xl font-extrabold text-center text-gray-900">{{__('Sign in to MeSort')}}</h1>
                <p class="mt-2 text-sm text-center text-gray-600">
                    Or
                    <a href="{{url('register')}}" class="font-medium text-blue-500 hover:text-blue-700"> {{__('Register to use
          MeSort')}}
                    </a>
                </p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">

                <input type="hidden" name="remember" value="true">
                @csrf
                <div class="-space-y-px rounded-md shadow-sm">
                    <div>
                        <label for="email-address" class="sr-only">{{ __('E-Mail Address') }}</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                               class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="{{ __('E-Mail Address') }}">
                    </div>
                    <div class="mb-2">
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="{{ __('Password') }}">
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
                            class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md group hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-blue-500 group-hover:text-blue-400"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                              <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"/>
                            </svg>
                          </span>
                        {{__("Login")}}
                    </button>
                </div>
            </form>
            <div class="flex items-center justify-between text-sm">
                <a onclick="event.preventDefault(); window.location.href='{{route('password.request')}}';"
                   href="{{route('password.reset.form')}}" class="font-medium text-blue-600 hover:text-blue-700"> {{__('Forgot your
            password?')}} </a>
            </div>
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
