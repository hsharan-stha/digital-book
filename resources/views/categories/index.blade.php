<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
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
                <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    + Add category
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6">
                <ul class="space-y-3">
                    @forelse($categories as $category)
                        <li class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-900 dark:text-gray-100">{{ $category->name }}</span>
                            <div class="space-x-2 flex items-center">
                                <a href="{{ route('categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-800" title="Edit">
                                    <!-- Heroicon: pencil-square -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 13l6.768-6.768a2 2 0 012.828 0l.172.172a2 2 0 010 2.828L12 17H9v-3z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete it?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <!-- Heroicon: trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-7" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">No any category</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>