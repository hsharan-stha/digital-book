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
                                        <!-- Heroicon: x -->
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
                    <!-- Left div (50%) -->
                    <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                        <div id="filelist" class="bg-gray-100 dark:bg-gray-600 p-4 rounded shadow">
                        </div>
                    </div>

                    <!-- Right div (50%) -->
                    <div class="w-full md:w-1/2 px-4">
                        <div class="bg-gray-100 dark:bg-gray-600 p-4 rounded shadow">
                            <div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md space-y-4">
                                <!-- Existing PNG/JPG uploader -->
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

                                <!-- === ADDED FOR PDF: Client-side PDF→PNG converter UI === -->
                                <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        📚 Convert PDF to PNG (runs in your browser, then uploads)
                                    </label>
                                    <input id="pdfFile" type="file" accept="application/pdf" class="block w-full text-sm mb-3">
                                    <div class="flex items-center gap-3">
                                        <input id="pdfBookId" type="hidden" value="{{ $book->id }}">
                                        <button id="convertBtn"
                                                class="bg-blue-600 text-white px-4 py-2 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
                                                disabled>
                                            Convert & Upload
                                        </button>
                                        <button id="cancelBtn"
                                                class="bg-gray-500 text-white px-3 py-2 rounded disabled:bg-gray-300 disabled:cursor-not-allowed"
                                                disabled>
                                            Cancel
                                        </button>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded overflow-hidden mt-3">
                                        <div id="pdfBar" class="h-full bg-blue-600" style="width:0%"></div>
                                    </div>
                                    <div id="pdfLog" class="text-xs text-gray-600 dark:text-gray-300 max-h-48 overflow-auto mt-2"></div>
                                </div>
                                <!-- === /ADDED FOR PDF === -->
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

            var files = document.getElementById('pages').files;
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

    <!-- === ADDED FOR PDF: PDF.js library === -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>


    <!-- === ADDED FOR PDF: Conversion logic === -->
    <script>
    (() => {
        const fileInput = document.getElementById('pdfFile');
        const convertBtn = document.getElementById('convertBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const bar = document.getElementById('pdfBar');
        const log = document.getElementById('pdfLog');
        const bookId = document.getElementById('pdfBookId')?.value || '';

        const UPLOAD_URL = "{{ route('pages.store') }}"; // same endpoint you already use
        let cancelFlag = false;

        fileInput?.addEventListener('change', () => {
            convertBtn.disabled = !fileInput.files?.[0];
            cancelBtn.disabled = true;
        });

        cancelBtn?.addEventListener('click', () => {
            cancelFlag = true;
            cancelBtn.disabled = true;
        });

        convertBtn?.addEventListener('click', async () => {
            const file = fileInput.files?.[0];
            if (!file) return;

            convertBtn.disabled = true;
            cancelBtn.disabled = false;
            cancelFlag = false;
            bar.style.width = '0%';
            log.innerHTML = '';

            try {
                const arrayBuffer = await file.arrayBuffer();
                const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
                const pdf = await loadingTask.promise;

                appendLog(`Loaded PDF: ${pdf.numPages} pages`);

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const SCALE = 2; // raise to 2.5–3 for sharper PNGs (bigger files)

                for (let i = 1; i <= pdf.numPages; i++) {
                    if (cancelFlag) { appendLog('⏹️ Cancelled by user.'); break; }

                    const page = await pdf.getPage(i);
                    const viewport = page.getViewport({ scale: SCALE });
                    canvas.width = Math.floor(viewport.width);
                    canvas.height = Math.floor(viewport.height);

                    // white background for transparent PDFs
                    ctx.save();
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.restore();

                    await page.render({ canvasContext: ctx, viewport }).promise;

                    const blob = await new Promise(res => canvas.toBlob(res, 'image/png', 0.92));
                    const pageno = i;

                    const form = new FormData();
                    form.append('_token', "{{ csrf_token() }}");
                    form.append('book_id', bookId);
                    form.append('pageno[]', String(pageno));      // server can map page numbers directly
                    form.append('pages[]', blob, `${pageno}.png`); // numeric filename also helps your validator

                    await fetch(UPLOAD_URL, { method: 'POST', body: form });

                    updateProgress(i, pdf.numPages);
                    appendLog(`✔ Uploaded page ${pageno}`);

                    // keep UI responsive on very large PDFs
                    if (i % 5 === 0) await microPause();
                }

                if (!cancelFlag) appendLog('✅ Finished converting & uploading.');
            } catch (e) {
                console.error(e);
                appendLog('❌ Error: ' + (e?.message || e));
            } finally {
                convertBtn.disabled = false;
                cancelBtn.disabled = true;
            }
        });

        function updateProgress(done, total) {
            const pct = Math.round((done / total) * 100);
            bar.style.width = pct + '%';
        }
        function appendLog(msg) {
            const t = new Date().toLocaleTimeString();
            log.innerHTML += `[${t}] ${msg}<br>`;
            log.scrollTop = log.scrollHeight;
        }
        function microPause() { return new Promise(r => setTimeout(r, 0)); }
    })();
    </script>
    <!-- === /ADDED FOR PDF === -->
</x-app-layout>
