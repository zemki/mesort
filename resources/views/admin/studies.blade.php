@extends('admin.layout')

@section('content')

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full mx-auto bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600">
                    <th class="px-6 py-4 text-sm font-semibold uppercase">
                        Title
                    </th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">
                        Author
                    </th>
                    <th class="px-6 py-4 text-sm font-semibold text-center uppercase">
                        Creation Date
                    </th>
                    <th class="px-6 py-4 text-sm font-semibold text-center uppercase">
                        Link
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($studies as $study)
                    <tr>
                        <td class="px-6 py-4 max-w-xs overflow-hidden">
                            <p class="truncate">
                                {{ $study->name }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="truncate">
                                {{ $study->creator()->email ?? '' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-sm">
                                {{ $study->created_at }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a class="inline-block text-blue-500 hover:underline font-bold"
                               href="{{ url('studies/'.$study->id) }}">
                                Manage
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
