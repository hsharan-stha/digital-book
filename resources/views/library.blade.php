<x-entry-layout>

  <!-- Header -->
  <div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">📁 Book Library</h1>
    <button onclick="openFolderModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Folder</button>
  </div>

  <!-- Main Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Unassigned Images -->
    <div class="bg-white rounded shadow p-4">
      <h2 class="text-lg font-semibold mb-4">🖼️ Unassigned Books</h2>
      <div id="imageList" class="space-y-4"></div>
    </div>

    <!-- Folders -->
    <div class="bg-white rounded shadow p-4">
      <h2 class="text-lg font-semibold mb-4">📂 Folders</h2>
      <div id="folders" class="space-y-4"></div>
    </div>
  </div>

  <!-- Folder Modal -->
  <div id="folderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
      <h3 class="text-lg font-bold mb-4">Create Folder</h3>
      <form onsubmit="createFolder(event)">
        <input type="text" id="folderNameInput" class="w-full p-2 border rounded mb-4" placeholder="Enter folder name" required />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
        <button type="button" onclick="closeFolderModal()" class="ml-2 text-gray-500">Cancel</button>
      </form>
    </div>
  </div>

  <!-- Move Image Modal -->
  <div id="moveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
      <h3 class="text-lg font-bold mb-4">Move Image</h3>
      <select id="moveSelect" class="w-full p-2 border rounded mb-4">
        <option value="">Unassigned</option>
      </select>
      <button onclick="confirmMove()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Move</button>
      <button onclick="closeMoveModal()" class="ml-2 text-gray-500">Cancel</button>
    </div>
  </div>

  <script>
    const sampleImages = [
      { id: 1, name: 'Nature.jpg', src: 'https://picsum.photos/id/1015/300/200' },
      { id: 2, name: 'City.jpg', src: 'https://picsum.photos/id/1016/300/200' },
      { id: 3, name: 'Forest.jpg', src: 'https://picsum.photos/id/1018/300/200' },
    ];

    let folders = {};
    let currentMoveImage = null;

    const imageList = document.getElementById('imageList');
    const foldersContainer = document.getElementById('folders');
    const moveModal = document.getElementById('moveModal');
    const moveSelect = document.getElementById('moveSelect');

    function renderImages() {
      imageList.innerHTML = '';
      sampleImages.forEach(img => {
        if (!img.folder) {
          const div = document.createElement('div');
          div.className = 'flex items-center space-x-4';
          div.innerHTML = `
            <img src="${img.src}" alt="${img.name}" class="w-20 h-20 object-cover rounded" />
            <div class="flex-1">
              <p class="font-semibold">${img.name}</p>
              <button onclick="openMoveModal(${img.id})" class="mt-2 bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">Move</button>
            </div>
          `;
          imageList.appendChild(div);
        }
      });
    }

    function createFolder(e) {
      e.preventDefault();
      const name = document.getElementById('folderNameInput').value.trim();
      if (name && !folders[name]) {
        folders[name] = [];
        updateFolders();
        document.getElementById('folderNameInput').value = '';
        closeFolderModal();
        renderImages();
      }
    }

    function updateFolders() {
      foldersContainer.innerHTML = '';
      Object.keys(folders).forEach(folderName => {
        const images = folders[folderName];
        const div = document.createElement('div');
        div.className = 'border rounded p-4 relative';
        div.innerHTML = `
          <div class="flex justify-between items-center mb-2">
            <h4 class="font-bold text-lg">${folderName} (${images.length})</h4>
            <button onclick="renameFolder('${folderName}')" class="text-blue-600 text-sm hover:underline">✏️ Rename</button>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            ${images.map(img => `
              <div class="space-y-1">
                <img src="${img.src}" alt="${img.name}" class="w-full h-24 object-cover rounded">
                <button onclick="openMoveModal(${img.id})" class="w-full text-sm bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">Move</button>
              </div>
            `).join('')}
          </div>
        `;
        foldersContainer.appendChild(div);
      });
    }

    function openMoveModal(imageId) {
      const allImages = [...sampleImages, ...Object.values(folders).flat()];
      currentMoveImage = allImages.find(img => img.id === imageId);
      moveSelect.innerHTML = `<option value="">Unassigned</option>` +
        Object.keys(folders).map(name => `<option value="${name}">${name}</option>`).join('');
      moveSelect.value = currentMoveImage.folder || "";
      moveModal.classList.remove('hidden');
      moveModal.classList.add('flex');
    }

    function confirmMove() {
      const targetFolder = moveSelect.value;
      const img = currentMoveImage;
      if (!img) return;

      // Remove from old place
      if (!img.folder) {
        const index = sampleImages.findIndex(i => i.id === img.id);
        if (index !== -1) sampleImages.splice(index, 1);
      } else {
        const index = folders[img.folder].findIndex(i => i.id === img.id);
        if (index !== -1) folders[img.folder].splice(index, 1);
      }

      // Add to new
      if (targetFolder === "") {
        delete img.folder;
        sampleImages.push(img);
      } else {
        img.folder = targetFolder;
        folders[targetFolder].push(img);
      }

      currentMoveImage = null;
      closeMoveModal();
      renderImages();
      updateFolders();
    }

    function renameFolder(oldName) {
      const newName = prompt("Enter new folder name:", oldName);
      if (!newName || folders[newName]) return;

      const images = folders[oldName];
      folders[newName] = images.map(img => {
        img.folder = newName;
        return img;
      });

      delete folders[oldName];
      updateFolders();
      renderImages();
    }

    function openFolderModal() {
      document.getElementById('folderModal').classList.remove('hidden');
      document.getElementById('folderModal').classList.add('flex');
    }

    function closeFolderModal() {
      document.getElementById('folderModal').classList.add('hidden');
      document.getElementById('folderModal').classList.remove('flex');
    }

    function closeMoveModal() {
      moveModal.classList.add('hidden');
      moveModal.classList.remove('flex');
    }

    renderImages();
  </script>

</x-entry-layout>