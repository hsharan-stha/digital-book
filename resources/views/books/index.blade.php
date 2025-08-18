<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('books.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    + Add Book
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6">
                <table class="w-full table-auto text-gray-900 dark:text-gray-100">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th class="px-4 py-2 text-left">
                                <form method="GET">
                                    <input type="text" name="name" value="{{ request('name') }}"
                                        class="w-full rounded border-gray-300 text-sm px-2 py-1 dark:bg-gray-700 dark:text-white dark:border-gray-500"
                                        placeholder="Search name">
                            </th>
                            <th class="px-4 py-2 text-center">
                                <input type="text" name="category" value="{{ request('category') }}"
                                    class="w-full rounded border-gray-300 text-sm px-2 py-1 text-center dark:bg-gray-700 dark:text-white dark:border-gray-500"
                                    placeholder="Search category">
                            </th>
                            <th class="px-4 py-2 text-center">
                                <input type="text" name="company" value="{{ request('company') }}"
                                    class="w-full rounded border-gray-300 text-sm px-2 py-1 text-center dark:bg-gray-700 dark:text-white dark:border-gray-500"
                                    placeholder="Search company">
                            </th>
                            <th class="px-4 py-2 text-left">
                                <input type="text" name="price" value="{{ request('price') }}"
                                    class="w-full rounded border-gray-300 text-sm px-2 py-1 dark:bg-gray-700 dark:text-white dark:border-gray-500" 
                                    placeholder="Search price">
                            </th>
                            <th class="px-4 py-2 text-center">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                    Filter
                                </button>
                                <a href="{{ route('books.index') }}"
                                    class="ml-2 text-gray-500 hover:text-black text-sm">Reset</a>
                                </form>
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-center">Category</th>
                            <th class="px-4 py-2 text-center">Company</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $book->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $book->category->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $book->company->name }}</td>
                            <td class="px-4 py-2">&yen;{{ $book->price }}</td>
                            <td class="px-4 py-2 text-right">
                                <div class="flex justify-end  items-center space-x-2">
                                    <!-- Edit -->
                                    <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-800" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13l6.768-6.768a2 2 0 012.828 0l.172.172a2 2 0 010 2.828L12 17H9v-3z"/>
                                        </svg>
                                    </a>
                                    <!-- Delete -->
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete it?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <a href="{{ route('books.pages.index', $book) }}" class="bg-green-800 hover:bg-blue-600 text-white px-4 py-1 rounded">
                                        Pages
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>