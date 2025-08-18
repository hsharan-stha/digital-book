<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New Book') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mb-5">
                <form id="form1" action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Book name:
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            placeholder="Example: History"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description:
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            placeholder="Example: History"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @if(Auth::user()->id==1)
                    <div class="mb-4">
                        <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Company:
                        </label>
                        <select
                            name="company_id"
                            id="company_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        >
                            <option value="">Select a company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif
                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category:
                        </label>
                        <select
                            name="category_id"
                            id="category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                        >
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Price:
                        </label>
                        <input
                            type="number"
                            name="price"
                            id="price"
                            value="{{ old('price') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600"
                            placeholder="Example: 6500"
                        >
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cover image:
                        </label>
                        <input
                            type="file"
                            name="image"
                            id="image"
                            accept="image/png, image/jpeg"
                            class="mt-1 block w-full text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600 focus:outline-none"
                        >
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="pages" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Pages (multiple images):
                        </label>
                        <input
                            type="file"
                            name="pages[]"
                            id="pages"
                            accept="image/png, image/jpeg"
                            multiple
                            class="mt-1 block w-full text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600 focus:outline-none"
                        >
                        @error('pages')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('books.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">
                            Cancel
                        </a>
                        <button id="sendButton" type="submit" class="bg-blue-600 text-white px-4 py-2 rounded disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Save
                        </button>
                    </div>
                </form>
            </div>
            <div id="filelist"class="bg-white dark:bg-gray-500 shadow sm:rounded-lg">
            </div>
        </div>
    </div>

    <script>       
        
        document.getElementById('pages').addEventListener('change', function() {
            document.getElementById("sendButton").disabled = false;
            document.getElementById("filelist").innerHTML ="";  
            var form = document.getElementById('form1');
            var formData = new FormData(form);

            // Get the file input
            var files = document.getElementById('pages').files;
            var errorFlag = 0;
            for (let i = 0; i < files.length; i++) {
                var name = files[i].name;
                var nameWithoutExtension = name.split('.').slice(0, -1).join('.');
                var filesize = formatBytes(files[i].size);

                var isValidSize = files[i].size <= (1024 * 1024);
                var isNumberName = /^\d+$/.test(nameWithoutExtension);

                let fileList = document.getElementById("filelist");

                if (isValidSize && isNumberName) {
                    fileList.innerHTML += `✅ ${name} (${filesize})<br>`;
                } else {
                    document.getElementById("sendButton").disabled = true;
                    fileList.innerHTML += `<strong>❌ ${name} (${filesize})</strong><br>`;
                }
            }

            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes'

                const k = 1024
                const dm = decimals < 0 ? 0 : decimals
                const sizes = ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']

                const i = Math.floor(Math.log(bytes) / Math.log(k))

                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
            }
        });
        

    </script>
</x-app-layout>
