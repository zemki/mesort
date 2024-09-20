@extends('admin.layout')

@section('content')

    <section class="bg-blue-300 w-full p-6 rounded-lg mb-6">
        <div class="container mx-auto">
            <h1 class="text-4xl font-extrabold mb-2">
                Hello, {{ $user->email }}.
            </h1>
            <h2 class="text-lg font-semibold">
                I hope you are having a great day!
            </h2>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="p-6 text-center bg-white rounded-lg shadow-md">
            <p class="text-2xl font-extrabold">{{ $usercount }}</p>
            <p class="text-lg font-medium">Users</p>
        </div>
        <div class="p-6 text-center bg-white rounded-lg shadow-md">
            <p class="text-2xl font-extrabold">{{ $studiescount }}</p>
            <p class="text-lg font-medium">Studies</p>
        </div>
        <div class="p-6 text-center bg-white rounded-lg shadow-md">
            <p class="text-2xl font-extrabold">{{ $interviewcount }}</p>
            <p class="text-lg font-medium">Interviews</p>
        </div>
        <div class="p-6 text-center bg-white rounded-lg shadow-md">
            <p class="text-2xl font-extrabold">{{ $actionscount }}</p>
            <p class="text-lg font-medium">Actions</p>
        </div>
        <div class="p-6 text-center bg-white rounded-lg shadow-md">
            <p class="text-2xl font-extrabold">{{ $occupiedstorage }}</p>
            <p class="text-lg font-medium">Storage occupied by Screenshots</p>
        </div>
    </section>

    <section class="w-full">
        <div class="text-center font-bold mb-4">
            <p class="text-xl">ACTIONS</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Author</th>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">URL</th>
                        <th class="px-4 py-2 border">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actions as $action)
                        <tr class="@if(strpos($action['description'], 'delete')) bg-red-100 text-red-800 @else bg-white @endif hover:bg-gray-100">
                            <td class="border px-4 py-2">{{ $action['id'] }}</td>
                            <td class="border px-4 py-2">{{ $action['user']['email'] }}</td>
                            <td class="border px-4 py-2">{{ $action['name'] }}</td>
                            <td class="border px-4 py-2">{{ $action['description'] }}</td>
                            <td class="border px-4 py-2">{{ $action['url'] }}</td>
                            <td class="border px-4 py-2">{{ $action['time'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $actions->links() }}
            </div>
        </div>
    </section>

@endsection
