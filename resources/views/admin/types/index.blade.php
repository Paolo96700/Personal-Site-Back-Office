<x-app-layout>
    @section('contents')

    {{-- *************************************************************** --}}

    <!-- @if (session('delete_success'))
        @php $type = session('delete_success') @endphp
        <div class="alert alert-danger dark:text-gray-100 mt-2">
            The Type "{{ $type->name }}" has moved to the trash
        </div>
    @endif -->

    @if (session('delete_success'))
    @php $type = session('delete_success') @endphp
        <div class="bg-red-500 text-white mt-2 p-4 rounded-lg">
            The Type "{{ $type->name }}" has moved to the trash
        </div>
    @endif

    {{-- *************************************************************** --}}

        <h2 class="mt-4 mb-4 text-2xl font-semibold leadi text-gray-100">Types</h2>
    
        <button type="button" class="my-2 px-8 py-2 font-semibold border rounded dark:border-gray-100 dark:text-gray-100 hover:bg-blue-500 hover:text-white">
            <a href="{{ route("admin.types.create") }}">Create a new Type</a>
        </button>

        <button class="mb-4 px-8 py-2 font-semibold border rounded dark:border-gray-100 dark:text-gray-100 hover:bg-red-700 hover:text-white">
            <a class="button mx-1" href="{{ route('admin.types.trashed') }}">Trash</a>
        </button>

        <div class="dark:text-gray-100">
            <table class="w-full p-6 text-xs text-left whitespace-nowrap">
                <colgroup>
                    <col class="w-5">
                    <col>
                    <col class="w-5">
                </colgroup>
                <thead>
                    <tr class="dark:bg-gray-700">
                        <th class="p-2">Name</th>
                        <th class="p-2">Description</th>
                        <th class="p-2">Actions</th>
                        
                    </tr>
                </thead>
                <tbody class="border-b dark:bg-gray-900 dark:border-gray-700">
                    @foreach ($types as $type)
                        <tr>
                            <td class="px-2 text-1xl font-medium dark:text-gray-400">
                                {{ $type->name }}
                            </td>
                            <td class="px-2 py-2">
                                <p>{{ $type->description }}</p>
                            </td>
                            <td class="flex pl-2 py-1 items-center">
                                <button class="px-1 py-1 font-semibold border rounded dark:border-gray-100 dark:text-gray-100 hover:bg-blue-500 hover:text-black">
                                    <a class="btn btn-primary" href="{{ route('admin.types.show', ['type' => $type->id]) }}">View</a>
                                </button>

                                <button class="ml-1 px-1 py-1 font-semibold border rounded dark:border-gray-100 dark:text-gray-100 hover:bg-yellow-500 hover:text-black">
                                    <a class="btn btn-warning" href="{{ route('admin.types.edit', ['type' => $type->id]) }}">Edit</a>
                                </button>
                                
                                <form
                                    action="{{ route('admin.types.destroy', ['type' => $type->id]) }}"
                                    method="post"
                                    class="d-inline-block mx-1"
                                >
                                    @csrf
                                    @method('delete')
                                    <button class="px-1 py-1 font-semibold border rounded dark:border-gray-100 dark:text-gray-100 hover:bg-red-700 hover:text-white">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    @endsection
</x-app-layout>

