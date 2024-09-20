@extends('auth.layouts.app')

@section('content')
@if(session()->has('message') || !empty($message))
<div class="p-4 rounded-md bg-red-50">
    <div class="flex">
        <div class="flex-shrink-0">
            <!-- Heroicon name: solid/x-circle -->
            <svg class="w-5 h-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
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
        @if (session('status'))
        <div class="py-2 pl-2 my-2 font-bold text-white bg-blue-500 rounded-none appearance-none ">
            {{session('status')}}
        </div>

        @endif
        <div>
            <img class="w-auto h-24 mx-auto" src="{{config('utilities.base64logo')}}" alt="Mesort Logo">
            <h1 class="mt-6 text-3xl font-extrabold text-center text-gray-900">{{__('Reset Password')}}</h1>
            <p class="mt-2 text-sm text-center text-gray-600">
                Or
                <a href="{{url('login')}}" class="font-medium text-blue-500 hover:text-blue-700"> {{__('Login')}}
                </a>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            <input type="hidden" name="remember" value="true">
            @csrf
            <div class="-space-y-px rounded-md shadow-sm">
                <div>
                    <label for="email-address" class="sr-only">{{ __('E-Mail Address') }}</label>
                    <input id="email-address" name="email" type="email" autocomplete="email"
                        class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-none appearance-none rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        value="{{ old('email') }}" required placeholder="{{ __('E-Mail Address') }}">
                </div>

            </div>
            @if ($errors->has('email'))
            <div class="py-2 pl-2 my-2 font-bold text-white bg-red-700 rounded-none appearance-none rounded-b-md">{{
                $errors->first('email') }}
            </div>
            @endif
            <div>
                <button type="submit"
                    class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md group hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <!-- Heroicon name: solid/lock-closed -->
                        <svg class="w-5 h-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>





@endsection