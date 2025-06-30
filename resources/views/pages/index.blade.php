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

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-5">
                <ul class="space-y-3 mt-6">
                    @forelse($pages as $page)
                        <li x-data="{ open: false }" class="flex justify-between items-center border-b pb-2">
                            <span class="text-white">{{ $loop->iteration }}</span>
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
                                        <img loading="lazy" src="{{ asset($page->page_image) }}"
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
            </div>

            <div class="bg-white dark:bg-gray-600 shadow overflow-hidden sm:rounded-lg p-6">
                <div class="flex flex-wrap -mx-4">
                    <!-- Chap div (50%) -->
                    <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                        <div id="filelist" class="bg-gray-100 dark:bg-gray-600 p-4 rounded shadow">
                        </div>
                    </div>

                    <!-- O'ng div (50%) -->
                    <div class="w-full md:w-1/2 px-4">
                        <div class="bg-gray-100 dark:bg-gray-600 p-4 rounded shadow">
                            <div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md space-y-4">
                                <form id="form1" action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label for="pages" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📄 Upload Book Pages
                                    </label>

                                    <div class="relative flex items-center justify-center w-full">
                                        <label
                                            for="pages"
                                            class="flex flex-col items-center justify-center w-full h-48 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                        >
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12M5 20h14a2 2 0 002-2V7a2 2 0 00-2-2h-3M15 10l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG or JPG (multiple files)</p>
                                            </div>
                                            <input
                                                id="pages"
                                                name="pages[]"
                                                type="file"
                                                accept="image/png, image/jpeg"
                                                multiple
                                                class="hidden"
                                            />
                                            <input type="hidden" name="book_id" value="{{ $book->id }}"/>
                                        </label>
                                    </div>

                                    @error('pages')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div class="flex justify-end pt-4">
                                        <button
                                            type="submit"
                                            id="addPageBtn"
                                            class="bg-blue-600 text-white px-4 py-2 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
                                        >                                            
                                            Add Pages
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        document.getElementById("addPageBtn").disabled = true;
        document.getElementById('pages').addEventListener('change', function() {
            
            document.getElementById("addPageBtn").disabled = false;
            
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

                if (isNumberName) {
                    fileList.innerHTML += `✅ ${name} (${filesize})<br>`;
                } else {
                    document.getElementById("addPageBtn").disabled = true;
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
