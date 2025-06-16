<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pages of') }} {{ $book->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6">
                <ul class="space-y-3">
                    @forelse($pages as $page)
                        <li class="flex justify-between items-center border-b pb-2">
                            <span class="text-gray-900 dark:text-gray-100">{{ $page->name }}</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $page->title }}</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $page->pageno }}</span>                            
                        </li>
                    @empty
                        <li class="text-gray-500">No any pages</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>