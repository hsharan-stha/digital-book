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
                <button id="addPageBtn" class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Add Page</button>

                <div id="pageFormWrapper"></div>

                <ul class="space-y-3 mt-6">
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

    <script>
        document.getElementById('addPageBtn').addEventListener('click', function () {
            const formWrapper = document.getElementById('pageFormWrapper');
            const form = document.createElement('form');
            form.classList.add('mb-4', 'bg-gray-100', 'p-4', 'rounded');
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="book_id" value="{{ $book->id }}">

                <div class="mb-2">
                    <input type="text" name="name" placeholder="Page Name" class="w-full rounded border p-2">
                </div>
                <div class="mb-2">
                    <input type="text" name="title" placeholder="Page Title" class="w-full rounded border p-2">
                </div>
                <div class="mb-2">
                    <input type="number" name="pageno" placeholder="Page Number" class="w-full rounded border p-2">
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded">Save</button>
            `;

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch("{{ route('pages.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Validation failed'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred.');
                });
            });

            formWrapper.appendChild(form);
        });
    </script>
</x-app-layout>
