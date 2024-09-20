@extends('admin.layout')

@section('content')

    <div class="content">
        <user-table :users="{{$users}}"></user-table>
    </div>
@endsection
