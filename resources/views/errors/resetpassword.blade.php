@extends('auth.layouts.app')

@section('content')

        <div class="flex items-center justify-center h-screen z-10">
            <div class="overflow-hidden shadow-lg  w-1/3">

            <div class="bg-red-700 border-t border-b border-red-500 text-white px-4 py-3" role="alert">
                This link is not valid, please contact the administrator.
            </div>

            <div class="py-4 text-center ">
                <a class="text-blue-500 hover:text-red-600" href="{{url('login')}}">{{__("Login Page")}}</a>
            </div>
            </div>
        </div>

@endsection
