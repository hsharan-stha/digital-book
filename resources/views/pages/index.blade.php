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
                <ul class="space-y-3 mt-6">
                    @forelse($pages as $page)
                        <li x-data="{ open: false }" class="flex justify-between items-center border-b pb-2">
                            <span @click="open = true"
                                class="cursor-pointer text-blue-600 hover:underline dark:text-blue-400">
                                Page {{ $page->pageno }}
                            </span>
                            <div class="space-x-2 flex items-center">
                                <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete it?')">
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

                            <!-- Modal -->
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                x-cloak @click.away="open = false">
                                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg max-w-xl w-full">
                                    <div class="flex justify-between items-center p-4 border-b">
                                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                            Page {{ $page->pageno }}
                                        </h2>
                                        <button @click="open = false" class="text-gray-600 dark:text-gray-300 hover:text-gray-900">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="p-4">
                                        <img src="{{ asset($page->page_image) }}"
                                            alt="Page {{ $page->pageno }}"
                                            class="mx-auto max-h-[70vh] object-contain">
                                    </div>
                                </div>
                            </div>
                            
                        </li>
                    @empty
                        <li class="text-gray-500">No any pages</li>
                    @endforelse
                </ul>

                <div id="pageFormWrapper" class="mt-6"></div>
                <div class="flex justify-end">
                    <button id="addPageBtn" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">+ Add Page</button>
                </div>
            </div>
        </div>
    </div>

<script>
    document.getElementById('addPageBtn').addEventListener('click', function () {        
        const addBtn = this;
        addBtn.disabled = true; // ✅ Tugmani o‘chiradi
        
        const formWrapper = document.getElementById('pageFormWrapper');
        const form = document.createElement('form');
        form.classList.add('mb-4', 'bg-gray-800', 'p-4', 'rounded', 'flex', 'flex-wrap', 'gap-4', 'items-end');

        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="book_id" value="{{ $book->id }}">

            <input type="text" name="name" placeholder="Page Name" required
                class="flex-1 min-w-[150px] rounded border border-gray-300 p-2 bg-gray-700 text-white">
            <input type="text" name="title" placeholder="Page Title" required
                class="flex-1 min-w-[150px] rounded border border-gray-300 p-2 bg-gray-700 text-white">
            <input type="number" name="pageno" placeholder="Page Number" required
                class="flex-1 min-w-[100px] rounded border border-gray-300 p-2 bg-gray-700 text-white">

            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded self-end">Add</button>
        `;

        

        // Submitni tutib olish
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch("{{ route('pages.store') }}", {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    const err = await response.json();
                    alert(err.message || 'Validation error.');
                    throw new Error(err.message || 'Error');
                }
                return response.json();
            })
            .then(data => {
                location.reload(); // Sahifani to‘liq yangilab qo‘yadi
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        formWrapper.appendChild(form);
    });
</script>
</x-app-layout>
