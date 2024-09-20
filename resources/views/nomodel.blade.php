@extends('errors.minimal')

@section('title', __('Not Found'))
@section('message', __($model.' not Found'))
@section('url')
    <br>
    <a href="{{ app('router')->has('home') ? route('home') : url('/') }}">
        <button>
            {{ __('Go Home') }}
        </button>
    </a>
@endsection
