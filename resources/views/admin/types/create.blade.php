<x-app-layout>
    @section('contents')

        <section class="m-5 p-4 dark:bg-gray-800 dark:text-gray-50">
            <div class="p-5">
                <h1 class="text-2xl font-bold">Create new Type</h1>

                <form method="POST" 
                action="{{ route('admin.types.store') }}" 
                class="container flex flex-col mx-auto space-y-12"
                novalidate>
                    @csrf

                    <fieldset class="grid grid-cols-4 gap-6 p-6 rounded-md shadow-sm dark:bg-gray-900">
                        <div class="space-y-2 col-span-full lg:col-span-1">
                            <p class="font-medium">Informations</p>
                            <p class="text-xs">Put the information here to create a new type</p>
                        </div>
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-6">

                            <div class="col-span-full sm:col-span-6">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900 form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                        </div>
            
                        <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-6">
                            <div class="col-span-full sm:col-span-6">
                                <label for="description" class="form-label">Description</label>
                                <textarea
                                    class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900 form-control @error('description') is-invalid @enderror"
                                    id="description"
                                    rows="10"
                                    name="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                        </div>
                    </fieldset>

                

                    <div class="flex justify-center p-6 rounded-md shadow-sm dark:bg-gray-900">
                        <button class="px-20 py-3 font-semibold rounded dark:bg-gray-100 dark:text-gray-800 col-span-full sm:col-span-1 btn">Create</button>
                    </div>
                </form>
            </div>
        </section>
    @endsection
</x-app-layout>

