<x-entry-layout>
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">📁 Book Library</h1>
        <div class="flex items-center space-x-4">
            <button id="addFolderBtn" onclick="openFolderModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 ">+ Add Folder</button>
            <label class="flex items-center space-x-2 text-sm font-medium hidden">
                <input type="checkbox" id="manageToggle" class="form-checkbox h-4 w-4 text-blue-600 "
                    onchange="toggleManageMode()" />
                <span>Manage Folder</span>
            </label>

        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">


        <!-- Folders (75% width on md+, full width on xs) -->
        <div class="bg-white rounded shadow p-4 flex flex-col overflow-hidden">
            <h2 class="text-lg font-semibold mb-4">📂 Folders</h2>
            <div id="foldersContainer"
                class="grid grid-cols-1 md:grid-cols-3  lg:grid-cols-5 min-h-[200px] overflow-y-auto flex-1">
                <!-- Folder lists here -->
            </div>
        </div>
    </div>

    <!-- Unassigned Books (25% width on md+, full width on xs) -->
    <div class="bg-white rounded shadow p-4 flex flex-col overflow-hidden">
        <h2 class="text-lg font-semibold mb-4">🖼️ Unassigned Books</h2>
        <div id="unassignedBooks"
            class="space-y-4 border border-dashed border-gray-300 rounded p-2 overflow-y-auto flex-1 flex"
            ondragover="dragOver(event)" ondrop="drop(event, null)">
            <!-- Unassigned books go here -->
        </div>
    </div>

    <!-- Folder Modal -->
    <div id="folderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
            <h3 class="text-lg font-bold mb-4">Create Folder</h3>
            <form onsubmit="createFolder(event)">
                <input type="text" id="folderNameInput" class="w-full p-2 border rounded mb-4"
                    placeholder="Enter folder name" required />
                <input type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer"
                    value="Create" />
                <button type="button" onclick="closeFolderModal()" class="ml-2 text-gray-500">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const sampleImages = @json($purchasesList);
        const initialFolders = @json($folders);

        let folders = {};
        let unassignedBooks = [];
        let dragSrcId = null;
        let manageMode = true;

        function initializeData() {
            folders = {};
            initialFolders.forEach(f => folders[f.name] = []);
            unassignedBooks = [];

            sampleImages.forEach(book => {
                if (book.folder && folders[book.folder]) {
                    folders[book.folder].push(book);
                } else {
                    unassignedBooks.push(book);
                }
            });
        }

        function renderUnassigned() {
            const container = document.getElementById('unassignedBooks');
            container.innerHTML = '';
            if (unassignedBooks.length === 0) {
                container.innerHTML = '<p class="text-gray-400">No unassigned books</p>';
                return;
            }
            unassignedBooks.forEach(book => {
                const div = document.createElement('div');
                div.className = "flex items-center space-x-4 p-2 border rounded bg-gray-50 hover:bg-gray-100 " + (
                    manageMode ? 'cursor-move' : '');
                if (manageMode) {
                    div.setAttribute('draggable', 'true');
                    div.ondragstart = dragStart;
                }
                div.setAttribute('data-id', book.id);

                div.innerHTML = `
                 <a href="/reader/${book.id}/reading" class="book-anchor">
                    <img loading="lazy" src="${book.src}" alt="${book.name}" class="w-20 h-20 object-cover rounded" />
                    <div class="flex-1 font-semibold">${book.name}</div>
                    </a>
                `;
                container.appendChild(div);
            });
        }

        function renderFolders() {
            const container = document.getElementById('foldersContainer');
            container.innerHTML = '';
            const folderNames = Object.keys(folders);
            if (folderNames.length === 0) {
                container.innerHTML = '<p class="text-gray-400">No folders created</p>';
                return;
            }

            folderNames.forEach(folderName => {
                const folderDiv = document.createElement('div');
                folderDiv.className = 'border rounded p-4 relative';

                if (manageMode) {
                    folderDiv.setAttribute('ondragover', 'dragOver(event)');
                    folderDiv.setAttribute('ondrop', `drop(event, '${folderName}')`);
                }
                // (${folders[folderName].length})
                folderDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-bold text-lg">${folderName} </h4>
                        ${manageMode ? `
                                                                                            <div class="space-x-2">
                                                                                                <button onclick="renameFolder('${folderName}')" class="text-blue-600 text-sm hover:underline">✏️</button>
                                                                                                <button onclick="deleteFolder('${folderName}')" class="text-red-600 text-sm hover:underline">🗑️</button>
                                                                                            </div>` : ''}
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 min-h-[80px]">
                        ${folders[folderName].map(book => `
                                                                                            <div class="flex flex-col items-center p-2 border rounded bg-gray-50 hover:bg-gray-100 ${manageMode ? 'cursor-move' : ''}"
                                                                                                ${manageMode ? `draggable="true" ondrop="sort('${book.id}', '${folderName}')" ondragstart="dragStart(event)"` : ''}
                                                                                                data-id="${book.id}">
                                                                                                <a href="/reader/${book.id}/reading" class="book-anchor">
                                                                                                <img loading="lazy"  src="${book.src}" alt="${book.name}" class="w-full h-full rounded" />
                                                                                                <!-- <div class="text-sm mt-1 font-semibold">${book.name}</div> -->
                                                                                                </a>
                                                                                            </div>
                                                                                        `).join('')}
                    </div>
                `;
                container.appendChild(folderDiv);
            });
            document.addEventListener("DOMContentLoaded", function() {
                DOMContentLoaded()
            });
        }

        function dragStart(e) {
            dragSrcId = e.currentTarget.getAttribute('data-id');
            e.dataTransfer.effectAllowed = 'move';
        }

        async function sort(targetId, folderName) {
            if (!dragSrcId || dragSrcId === targetId) return;

            // Optional: verify both source and target exist in the same folder
            const booksInFolder = folders[folderName] || [];
            const sourceExists = booksInFolder.some(b => b.id == dragSrcId);
            const targetExists = booksInFolder.some(b => b.id == targetId);

            if (!sourceExists || !targetExists) {
                // alert("Both items must be in the same folder to sort.");
                return;
            }

            // Call backend to swap orders
            const res = await fetch("{{ route('library.sort') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    source_id: dragSrcId,
                    target_id: targetId,
                    folder_name: folderName
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                // Re-render after sort
                await refreshBooks(folderName);
            } else {
                alert(data.error || "Sort failed.");
            }

            dragSrcId = null;
        }

        async function refreshBooks(folderName) {
            const res = await fetch(`/library/folder/${encodeURIComponent(folderName)}/books`);
            const books = await res.json();
            console.log(books)
            folders[folderName] = books;
            renderFolders();
        }


        function dragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }

        async function drop(e, targetFolder) {
            e.preventDefault();
            if (!dragSrcId) return;

            // Find dragged book & source folder
            let draggedBook = unassignedBooks.find(b => b.id == dragSrcId);
            let sourceFolder = null;

            if (!draggedBook) {
                for (const [fname, books] of Object.entries(folders)) {
                    const idx = books.findIndex(b => b.id == dragSrcId);
                    if (idx !== -1) {
                        draggedBook = books[idx];
                        sourceFolder = fname;
                        break;
                    }
                }
            }

            // If no book found or dropping in the same folder, do nothing
            if (!draggedBook || sourceFolder === targetFolder) {
                dragSrcId = null;
                return;
            }

            // If targetFolder is null or empty string => unassigned
            const folderNameToSend = targetFolder || null;

            const res = await fetch("{{ route('library.move') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    book_id: draggedBook.id,
                    folder_name: folderNameToSend
                })
            });

            const data = await res.json();
            if (data.success) {
                // Remove from old location
                if (sourceFolder) {
                    folders[sourceFolder] = folders[sourceFolder].filter(b => b.id != draggedBook.id);
                } else {
                    unassignedBooks = unassignedBooks.filter(b => b.id != draggedBook.id);
                }

                // Add to new location
                if (folderNameToSend) {
                    folders[folderNameToSend].push(draggedBook);
                    draggedBook.folder = folderNameToSend;
                } else {
                    unassignedBooks.push(draggedBook);
                    delete draggedBook.folder;
                }

                renderUnassigned();
                renderFolders();
            } else {
                alert("Move failed.");
            }

            dragSrcId = null;
        }

        async function createFolder(e) {
            e.preventDefault();
            const name = document.getElementById('folderNameInput').value.trim();
            if (!name) return;

            const res = await fetch("{{ route('folder.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    name
                })
            });

            const data = await res.json();

            if (res.ok && data.name && !folders[data.name]) {
                folders[data.name] = [];
                renderUnassigned();
                renderFolders();
                document.getElementById('folderNameInput').value = '';
                closeFolderModal();
            } else {
                alert(data.message || "Folder creation failed.");
            }
        }

        async function renameFolder(oldName) {
            const newName = prompt("New folder name:", oldName);
            if (folders[newName]) return alert("Invalid or duplicate name.");

            const res = await fetch("{{ route('folder.rename') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    old_name: oldName,
                    new_name: newName
                })
            });

            const data = await res.json();
            if (data.success) {
                folders[newName] = folders[oldName].map(b => {
                    b.folder = newName;
                    return b;
                });
                delete folders[oldName];
                renderFolders();
                renderUnassigned();
            } else {
                alert("Rename failed.");
            }
        }

        async function deleteFolder(name) {
            if (!confirm(`Delete folder "${name}"? Books will go to Unassigned.`)) return;

            const res = await fetch("{{ route('folder.destroy') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    name
                })
            });

            const data = await res.json();
            if (data.success) {
                folders[name].forEach(b => {
                    delete b.folder;
                    unassignedBooks.push(b);
                });
                delete folders[name];
                renderFolders();
                renderUnassigned();
            } else {
                alert("Delete failed.");
            }
        }

        function openFolderModal() {
            document.getElementById('folderModal').classList.remove('hidden');
            document.getElementById('folderModal').classList.add('flex');
        }

        function closeFolderModal() {
            document.getElementById('folderModal').classList.add('hidden');
            document.getElementById('folderModal').classList.remove('flex');
        }

        function toggleManageMode() {
            manageMode = document.getElementById('manageToggle').checked;
            document.getElementById('addFolderBtn').classList.toggle('hidden', !manageMode);
            renderUnassigned();
            renderFolders();
        }

        initializeData();
        renderUnassigned();
        renderFolders();
    </script>
    <script>
        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}")
        loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }})
    </script>
    <script>
        window.addEventListener("pageshow", function(event) {
            // This will run on both normal and bfcache restores
            document.querySelectorAll(".book-anchor").forEach(function(anchor) {
                anchor.disabled = false;
                anchor.innerHTML = anchor.dataset.originalText;
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            DOMContentLoaded()
        });

        function DOMContentLoaded() {
            document.querySelectorAll(".book-anchor").forEach(function(anchor) {
                // Save original button text
                anchor.dataset.originalText = anchor.innerHTML;

                anchor.addEventListener("click", function(e) {
                    anchor.disabled = true;
                    anchor.innerHTML = '<span class="ml-2">Loading...</span>';
                });
            });
        }
    </script>
</x-entry-layout>
