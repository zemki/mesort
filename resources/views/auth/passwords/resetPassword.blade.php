@extends('auth.layouts.app')

@section('content')

    <div class="flex sm:items-start md:items-center md:justify-center h-screen">
        <div class="bg-white p-4 rounded overflow-hidden shadow-lg sm:w-full md:w-1/2 lg:w-1/3">
            @if (session('status'))
                <div class="flex relative px-3 py-3 mb-4 border text-green-800 bg-green-200">
                    {{ session('status') }}
                </div>
            @endif

            @if(session()->has('message') || !empty($message))
                <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                    <p class="font-bold">{{session()->get('message') ? session()->get('message') : $message}}</p>
                </div>
            @endif

            <div class="text-center">
                <h1 class="text-4xl pb-2 m-auto max-w-full font-extrabold" style="margin: 0 auto; max-width: 100%;">
                    Mesort</h1>
                <h4> {{ __('Reset Password') }} </h4>
                <div class="py-4 w-full text-center">
                    <a class="text-blue-500 hover:text-red-600 block"
                       href="{{url('login')}}">{{__("Return to login")}}</a>
                </div>
            </div>
            <form method="POST" action="{{ url('password/reset') }}">
                @csrf
                <input type="hidden" value="{{$token}}" name="token"></input>
                <input type="hidden" value="{{$email}}" name="email"></input>
                <div class="mb-4 flex flex-wrap">
                    <label for="email"
                           class="md:w-1/3 mr-2 pl-4 pt-2 pb-2 mb-0 leading-normal">{{ __('E-Mail Address') }}</label>

                    <div class="md:w-1/2">
                        <input id="email" disabled type="email"
                               class="input {{ $errors->has('email') ? ' bg-red-dark' : '' }}" name="email"
                               value="{{ old('email') }}" required autofocus>

                    </div>
                </div>
                <div class="mb-4 flex flex-wrap">
                    <label for="email"
                           class="md:w-1/3 pl-4 pt-2 pb-2 mb-0 leading-normal">{{ __('Password') }}</label>

                    <div class="md:w-1/2">
                        <input id="password" type="password"
                               class="input {{ $errors->has('password') ? ' bg-red-dark' : '' }}" name="password"
                               minlength="6"
                               required></input>

                    </div>
                </div>
                <div class="mb-4 flex flex-wrap">
                    <label for="email"
                           class="md:w-1/3 pl-4 pt-2 pb-2 mb-0 leading-normal">{{ __('Confirm Password') }}</label>

                    <div class="md:w-1/2">


                        <input id="password-confirm" type="password"
                               class="input {{ $errors->has('password') ? ' bg-red-dark' : '' }}"
                               name="password_confirmation" required></input>

                    </div>
                </div>


                @if ($errors->has('password'))
                    <div class="bg-red-700 my-2 pl-2 py-2 text-white font-bold">{{ $errors->first('password') }}
                    </div>
                @endif


                <div class="pr-4 pl-4  text-center">
                    <button type="submit"
                            class="inline-block align-middle text-center select-none border font-normal whitespace-nowrap py-2 px-4 rounded text-base leading-normal no-underline text-blue-lightest bg-blue hover:bg-blue-light">
                        {{ __('Set password') }}
                    </button>
                </div>

            </form>
        </div>
    </div>



@endsection
