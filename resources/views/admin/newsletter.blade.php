@extends('admin.layout')

@section('content')


    <div>
        @if(isset($message))

            <span @created="showtoast({{$message}})"></span>

        @endif
        <h1 class="text-4xl font-bold mb-2">Users that agreed to receive emails</h1>
            <table class="table-fixed w-full border border-solid">
                <thead class="border border-b-1 border-solid bg-black">
                <tr>
                    <th class="px-4 py-2 text-white">ID</th>
                    <th class="px-4 py-2 text-white">Email</th>
                </tr>
                </thead>
                <tbody>

        @foreach($users as $user)
            <tr class="hover:bg-gray-100">
                <td class="border px-4 py-2">{{$user->id}}</td>
                <td class="border px-4 py-2">{{$user->email}}</td>

            </tr>
        @endforeach
        </tbody>
            </table>
    </div>


@endsection
