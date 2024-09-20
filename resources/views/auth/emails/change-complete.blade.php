@extends('auth.layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-full px-4 py-12 sm:px-6 lg:px-8">
  <div class="w-full max-w-md space-y-8">
    <div>
      <img class="w-auto h-24 mx-auto" src="{{config('utilities.base64logo')}}" alt="Mesort Logo">
      <h1 class="mt-6 text-3xl font-extrabold text-center text-gray-900">{{__('Your email was changed successfully!')}}</h1>
      <p class="mt-2 text-sm text-center text-gray-600">
        
        <a href="{{url('/')}}" class="font-medium text-blue-500 hover:text-blue-700"> {{__('Home')}}
        </a>
      </p>
    </div>
    
  </div>
</div>
@endsection
@section('pagespecificscripts')

@endsection