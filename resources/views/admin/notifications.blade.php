@extends('admin.layout')

@section('content')

    @if(isset($message))

        <span @created="showtoast('{{$message}}')"></span>

    @endif

    <h1 class="title">Create a Notification</h1>
    <form method="POST" action="{{url('admin/notify')}}" class="">

        @csrf
        <input type="hidden" value="2" name="role">

        <label for="name" class="label">
            Email
        </label>

        <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring w-1/4" name="email" >


        <input type="checkbox" name="all" value="true"> Send to all users<br>


        <label for="name" class="label">
            Title
        </label>

        <input type="text" class="input" name="title">

        <label for="email" class="label">
            Notification Message
        </label>
        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="message" rows="3" cols="10"></textarea>


        <button class="button is-link">Send notification</button>


    </form>


@endsection
