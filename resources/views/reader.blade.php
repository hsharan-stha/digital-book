<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/jquery/jquery.min.1.7.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery-ui-1.8.20.custom.min.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('js/lib/paltau.min.js') }}"></script>
    <script src="{{ asset('js/lib/tesseract.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/book/jquery.ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/book/jquery.ui.html4.css') }}" />



    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body>
    <div id="topSection" class="hidden">
        <div style="display: flex;justify-content: space-between;   padding: 12px 20px;gap:1rem">
            <div style="display: flex;flex-wrap: wrap;gap: 1rem;width: 100%; justify-content: space-between; ">
                <div style="display: flex;justify-content: center;align-items: center;"> <a id="libraryPage"
                        href="/library" class="hidden">ライブラリに戻る</a></div>
                <div id="readerApp">
                    <div id="controlsSectionParent">
                        <div id="controlsSection">
                            <div style="display:flex; justify-content:center; gap:10px">
                                <button id="togglePlay" onclick="togglePlayPause()">
                                    ▶️
                                </button>
                            </div>
                            <input id="seekSlider" type="range" min="0" max="0" value="0">
                            <!-- Progress Label -->
                            <div id="progressLabel">
                                0 / 0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="flipbook">
        @forelse ($pages as $page)
            <div class="container">
                <div id="bookmark">
                    <div id="unmark-{{$page->pageno}}" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
                        </svg>
                    </div>
                    <div id="mark-{{$page->pageno}}" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- LAZY-LOAD: Use data-src (no src) so nothing downloads until JS opts-in -->
                 <div class="image-wrapper">
                <img
                    data-src="{{ asset($page->page_image) }}"
                    decoding="sync"
                    referrerpolicy="no-referrer"
                    onload="this.style.opacity='1'; this.parentNode.classList.add('loaded');"
                />
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <div id="pagination" class="pagination-container hidden">
        <div class="pagination-desktop">
            <div class="pagination-info">
                <p>
                    <svg id="bookmarkBtn" width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M6 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3H6Zm1.5 1.5a.75.75 0 0 0-.75.75V16.5a.75.75 0 0 0 1.085.67L12 15.089l4.165 2.083a.75.75 0 0 0 1.085-.671V5.25a.75.75 0 0 0-.75-.75h-9Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span id="currentPage" class="font-medium">1</span>/
                    <span id="pages" class="font-medium">-</span> ページ
                </p>
            </div>

            <div class="pagination-nav">
                <a href="#" id="backPage" class="pagination-arrow left">
                    <span class="sr-only">Back</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path
                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" />
                    </svg>
                </a>

                <select id="pageInput" name="page" class="pagination-select"></select>
            </div>
        </div>

        <div>
            <div id="slider-bar" class="flipjs-slider">
                <div id="slider"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="toc-wrapper">
            <h3 class="toc-title">ブックマーク</h3>
            <ul id="toc" class="toc-list"></ul>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
</body>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", async function() {
    // ============================
    // LAZY-LOAD HELPERS (only load current/near pages)
    // ============================
    const PRELOAD_RANGE = 4;                  // keep current ±2 loaded
    const UNLOAD_BEYOND = PRELOAD_RANGE + 1;  // unload if farther than this

    function loadImgForPage(n) {
        const el = document.querySelector(`#flipbook [page="${n}"] img`);
        if (el && !el.src) {
            el.src = el.dataset.src;   // trigger network load now
            el.loading = 'eager';
        }
    }
    function unloadImgForPage(n) {
        const el = document.querySelector(`#flipbook [page="${n}"] img`);
        if (el && el.src) {
            el.removeAttribute('src'); // free memory & network decoding
        }
    }
    function lazyLoadAround(center) {
        const total = $("#flipbook").paltau("pages");
        const start = Math.max(1, center - PRELOAD_RANGE);
        const end   = Math.min(total, center + PRELOAD_RANGE);

        for (let p = start; p <= end; p++) loadImgForPage(p);

        // Unload far-away pages
        document.querySelectorAll('#flipbook [page]').forEach(node => {
            const p = +node.getAttribute('page');
            if (p < start - UNLOAD_BEYOND || p > end + UNLOAD_BEYOND) {
                unloadImgForPage(p);
            }
        });

        // In double display, ensure both visible pages are loaded
        const view = ($("#flipbook").paltau("view") || []);
        view.forEach(v => loadImgForPage(v));
    }
    // ============================

    // Helper to load/save book data (server-backed)
    const sessionData = @json($sessionData);
    async function loadBook(bookId) {
        return await sessionData || { bookId, currentPage: 1, bookmarks: [] };
    }
    async function saveBook(bookData) {
        try {
            const response = await fetch('/reader/session/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(bookData),
            });
            await response.json();
        } catch(e) {
            console.warn('Save failed:', e);
        }
    }

    const bookId = "{{ $book_id }}";
    let currentBook = await loadBook(bookId);

    let display = window.innerWidth > 992 ? "double" : "single";
    $("#flipbook").paltau({
        display: display,
        autoCenter: true,
        acceleration: false,
        gradients: true,
        page: currentBook.currentPage || 1,
        elevation: 100,
        duration: 1000,
    });
    

    const totalPages = $("#flipbook").paltau("pages");
    document.getElementById("pages").innerText = totalPages;
    const pageWhileLoaded = $("#flipbook").paltau("page");
    document.getElementById("currentPage").innerText = pageWhileLoaded;

    // LAZY-LOAD: prime initial spread
    lazyLoadAround(pageWhileLoaded);

    // Populate page selector dropdown
    const select = document.getElementById("pageInput");
    for (let i = 1; i <= totalPages; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = `${i} ページ`;
        select.appendChild(option);
    }
    document.getElementById("pageInput").value = pageWhileLoaded;

    // Update on page paltau: save state + lazy load
    $("#flipbook").bind("turned", async function(event, page) {
        currentBook.currentPage = page;
        await saveBook(currentBook);

        document.getElementById("currentPage").innerText = page;
        document.getElementById("pageInput").value = page;

        refreshSelect(page);
        toggleBookmark();
        $('#slider').slider('value', page);

        // for speech case
        textExtracted = false;
        if (speechSynthesis) speechSynthesis.cancel();
        isReading = false;
        document.getElementById("togglePlay").innerText = "▶️";

        // LAZY-LOAD: update window
        lazyLoadAround(page);
    });

    window.addEventListener("orientationchange", updateFlipbookDisplay);
    window.addEventListener("resize", updateFlipbookDisplay);
    updateFlipbookDisplay();

    function updateFlipbookDisplay($event) {
        const isPortrait = window.innerHeight > window.innerWidth;
        const displayMode = isPortrait ? "single" : "double";
        $("#flipbook").paltau("display", displayMode);
        $("#flipbook").height("100%");
        $("#flipbook").width("100%");
        // Keep visible pages loaded after layout change
        const page = $("#flipbook").paltau("page");
        lazyLoadAround(page);
    }

    swipeEvent(document.getElementById("flipbook"));

    document.body.addEventListener("keydown", (evt) => {
        if (evt.key == "ArrowRight") $("#flipbook").paltau("next");
        if (evt.key == "ArrowLeft")  $("#flipbook").paltau("previous");
    }, false);

    function swipeEvent(body) {
        let xDown = null;
        let yDown = null;
        let pinchDetected = false;

        body.addEventListener("touchstart", handleTouchStart, false);
        body.addEventListener("touchmove", handleTouchMove, false);
        body.addEventListener("touchend", handleTouchEnd, false);

        function getDistance(touches) {
            const dx = touches[0].clientX - touches[1].clientX;
            const dy = touches[0].clientY - touches[1].clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }
        function handleTouchStart(evt) {
            pinchDetected = false;
            if (evt.touches.length === 1) {
                xDown = evt.touches[0].clientX;
                yDown = evt.touches[0].clientY;
            }
        }
        function handleTouchMove(evt) {
            if (evt.touches.length === 2) pinchDetected = true;
        }
        function isZoomed() {
            if (window.visualViewport) return window.visualViewport.scale !== 1;
            return Math.round(window.devicePixelRatio * 100) !== 100;
        }
        function handleTouchEnd(evt) {
            if (pinchDetected) return;
            if (!xDown || !yDown) return;
            if (isZoomed()) return;

            const xUp = evt.changedTouches[0].clientX;
            const yUp = evt.changedTouches[0].clientY;

            const dx = xUp - xDown;
            const dy = yUp - yDown;

            if (Math.abs(dx) > Math.abs(dy)) {
                if (dx > 50) fadeAndTurnPage("right");
                else if (dx < -50) fadeAndTurnPage("left");
            }
            xDown = null;
            yDown = null;
        }
        function fadeAndTurnPage(direction) {
            const viewer = document.getElementById("flipbook");
            viewer.classList.remove("opacity-100");
            viewer.classList.add("opacity-0");
            if (direction === "left") $("#flipbook").paltau("next");
            else if (direction === "right") $("#flipbook").paltau("previous");
            setTimeout(() => {
                viewer.classList.remove("opacity-0");
                viewer.classList.add("opacity-100");
            }, 100);
        }
    }

    localStorage.removeItem("currentPageHistory");
    document.getElementById("backPage").style.display = "none";

    document.getElementById("pageInput").addEventListener("change", async function() {
        const selectedPage = parseInt($(this).val());
        currentBook.currentPageHistory = currentBook.currentPage;
        await saveBook(currentBook);
        document.getElementById("backPage").style.display = "flex";
        $("#flipbook").paltau("page", selectedPage);
        // lazy load handled by "turned" event
    });

    document.getElementById("flipbook").addEventListener("dblclick", function() {
        const controls = document.getElementById("pagination");
        const libraryPage = document.getElementById("libraryPage");
        const topSection = document.getElementById("topSection");

        controls.classList.toggle("hidden");
        libraryPage.classList.toggle("hidden");
        topSection.classList.toggle("hidden");
        toggleBookmark();

        // keep visible images ensured
        const page = $("#flipbook").paltau("page");
        lazyLoadAround(page);
    });

    document.getElementById("backPage").addEventListener("click", async function() {
        if (currentBook.currentPageHistory) {
            $("#flipbook").paltau("page", currentBook.currentPageHistory);
            refreshSelect(currentBook.currentPageHistory);
            document.getElementById("backPage").style.display = "none";
            delete currentBook.currentPageHistory;
            await saveBook(currentBook);
        }
    });

    function refreshSelect(page) {
        const pageInput = document.getElementById("pageInput");
        pageInput.blur();
        const $select = $(pageInput);
        setTimeout(() => {
            $select.find("option").prop("selected", false);
            $select.find(`option[value='${page}']`).prop("selected", true);
        }, 10);
    }

    // ===== Bookmarks Logic =====
    let bookmarksArray = currentBook.bookmarks || [];

    function loadBookMark() {
        const tocList = document.getElementById("toc");
        tocList.innerHTML = "";
        if (bookmarksArray.length === 0) {
            const li = document.createElement("li");
            li.style.display = "flex";
            li.style.alignItems = "center";
            li.style.gap = "8px";
            li.style.marginBottom = "8px";
            li.className = "toc-item";
            li.textContent = "ブックマークはありません";
            tocList.appendChild(li);
        } else {
            bookmarksArray.forEach((item) => {
                const li = document.createElement("li");
                li.style.display = "flex";
                li.style.alignItems = "center";
                li.style.gap = "8px";
                li.style.marginBottom = "8px";
                li.className = "toc-item";

                const span = document.createElement("span");
                span.className = "detail";
                span.textContent = `${item?.page} ページ`;

                const svgBookmark = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>`;

                const iconWrapper = document.createElement("span");
                iconWrapper.innerHTML = svgBookmark;
                iconWrapper.style.padding="0 1rem";
                iconWrapper.onclick = async (e) => {
                    e.stopPropagation();
                    // if (confirm(`Are you sure you want to delete bookmark on page ${item.page}?`)) {
                        const pageStr = item.page;
                        let bookmarkSet = new Set((bookmarksArray || []).map(b => String(b.page)));
                        bookmarksArray = bookmarksArray.filter(b => String(b.page) !== pageStr);
                        bookmarkSet.delete(pageStr);
                        currentBook.bookmarks = bookmarksArray;
                        await saveBook(currentBook);
                        toggleBookmark();
                        loadBookMark();
                        addEventListenerInBookMark();
                    // }
                };

                li.appendChild(span);
                li.appendChild(iconWrapper);
                li.onclick = () => { $("#flipbook").paltau("page", item?.page); };
                tocList.appendChild(li);
            });
        }
    }

    function toggleBookmark() {
        const view = Array.from($("#flipbook").paltau("view"));
        view.forEach(i => {
            const mark   = document.getElementById(`mark-${i}`);
            const unmark = document.getElementById(`unmark-${i}`);
            if (mark && unmark) {
                const hasBookmark = bookmarksArray.some(b => b.page == i);
                if (hasBookmark){ mark.classList.remove("hidden"); unmark.classList.add("hidden"); }
                else { unmark.classList.remove("hidden"); mark.classList.add("hidden"); }
            }
        });
    }

    function addEventListenerInBookMark() {
        const $flip = $("#flipbook");
        let bookmarkSet = new Set((bookmarksArray || []).map(b => String(b.page)));
        $flip.off(".bookmarks");
        let busy = false;
        const selector = '[id^="mark-"], [id^="unmark-"]';

        $flip.on("pointerup.bookmarks", selector, async function (e) {
            if (e.pointerType && !/^(touch|mouse|pen)$/.test(e.pointerType)) return;
            e.preventDefault(); e.stopPropagation();
            if (busy) return; busy = true;
            try {
                const id = this.id || "";
                const m = id.match(/^(unmark|mark)-(.+)$/);
                if (!m) return;
                const action  = m[1];
                const pageStr = String(m[2]);
                const cur   = String($flip.paltau("page"));
                const view  = ($flip.paltau("view") || []).map(String);
                const isVisible = pageStr === cur || view.includes(pageStr);
                if (!isVisible) return;
                const $page = $flip.find(`[page="${pageStr}"]`);
                if ($page.length === 0) return;
                if (bookmarkSet.size !== (bookmarksArray || []).length) {
                    bookmarkSet = new Set((bookmarksArray || []).map(b => String(b.page)));
                }
                if (action === "mark") {
                    if (bookmarkSet.has(pageStr)) {
                        bookmarksArray = bookmarksArray.filter(b => String(b.page) !== pageStr);
                        bookmarkSet.delete(pageStr);
                        currentBook.bookmarks = bookmarksArray;
                        await saveBook(currentBook);
                    }
                } else {
                    if (!bookmarkSet.has(pageStr)) {
                        const $img = $page.find("img").first();
                        const src  = $img.attr("src") || $img.attr("data-src") || "";
                        bookmarksArray.push({ page: pageStr, detail: src });
                        bookmarkSet.add(pageStr);
                        currentBook.bookmarks = bookmarksArray;
                        await saveBook(currentBook);
                    }
                }
                requestAnimationFrame(() => { toggleBookmark(); loadBookMark(); });
            } catch (err) {
                console.error("Bookmark toggle failed:", err);
            } finally {
                setTimeout(() => { busy = false; }, 150);
            }
        });

        $flip.on("keydown.bookmarks", selector, function (e) {
            if (e.key === "Enter" || e.key === " ") { e.preventDefault(); $(this).trigger("pointerup"); }
        });
    }

    loadBookMark();
    toggleBookmark();
    addEventListenerInBookMark();

    // Sidebar toggle for bookmarks
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    const hoverZone = document.getElementById("bookmarkBtn");

    hoverZone.addEventListener("click", () => {
        sidebar.style.transform = "unset";
        overlay.style.display = "flex";
        loadBookMark();
    });
    function closeSidebar() {
        sidebar.style.transform = "translateX(-100%)";
        overlay.style.display = "none";
    }
    overlay.addEventListener("click", closeSidebar);
    document.addEventListener("click", (event) => {
        if (!sidebar.contains(event.target) && !hoverZone.contains(event.target)) closeSidebar();
    });

    // Slider initialization
    $("#slider").slider({
        min: 1,
        step: 1,
        max: totalPages,
        start: function(event, ui) {
            $(".thumbnail").remove();
            _thumbPreview = $('<div />', { class: "thumbnail" }).html(`<div style="width:90px">${ui.value} ページ</div>`);
            _thumbPreview.appendTo($(ui.handle));
        },
        slide: function(event, ui) {
            $(".thumbnail").remove();
            _thumbPreview = $('<div />', { class: "thumbnail" }).html(`<div style="width:90px">${ui.value} ページ</div>`);
            _thumbPreview.appendTo($(ui.handle));
        },
        stop: function(event, ui) {
            $("#flipbook").paltau("page", ui.value);
            $('#slider').slider('value', ui.value);
        },
    });
    $('#slider').slider('value', pageWhileLoaded);
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const page = document.querySelector('#flipbook');

    let scale = 1;
    const scaleStep = 0.1;
    const maxScale = 3;
    const minScale = 1;

    let isDragging = false;
    let startX, startY;
    let translateX = 0, translateY = 0;

    function applyTransform() {
        page.style.transform = `scale(${scale}) translate(${translateX / scale}px, ${translateY / scale}px)`;
    }

    page.addEventListener('wheel', (e) => {
        e.preventDefault();
        const rect = page.getBoundingClientRect();
        const offsetX = e.clientX - rect.left;
        const offsetY = e.clientY - rect.top;
        const percentX = (offsetX / rect.width) * 100;
        const percentY = (offsetY / rect.height) * 100;
        page.style.transformOrigin = `${percentX}% ${percentY}%`;
        if (e.deltaY < 0) scale += scaleStep; else scale -= scaleStep;
        scale = Math.min(Math.max(scale, minScale), maxScale);
        applyTransform();
    });

    page.addEventListener('mousemove', (e) => {
        if (!isDragging && scale > 1) {
            const rect = page.getBoundingClientRect();
            const offsetX = e.clientX - rect.left;
            const offsetY = e.clientY - rect.top;
            const percentX = (offsetX / rect.width) * 100;
            const percentY = (offsetY / rect.height) * 100;
            page.style.transformOrigin = `${percentX}% ${percentY}%`;
        }
    });

    page.addEventListener('mousedown', (e) => {
        if (scale <= 1) return;
        isDragging = true;
        startX = e.clientX - translateX;
        startY = e.clientY - translateY;
        page.style.cursor = 'grabbing';
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        translateX = e.clientX - startX;
        translateY = e.clientY - startY;
        applyTransform();
    });

    window.addEventListener('mouseup', () => {
        isDragging = false;
        page.style.cursor = 'default';
    });

    page.addEventListener('click', () => {
        scale = 1; translateX = 0; translateY = 0; page.style.transformOrigin = 'center'; applyTransform();
    });
});
</script>

<script>
let speechQueue = [];
let currentIndex = 0;
let isReading = false;
let voicesReady = false;
let selectedVoice = null;
let textExtracted = false;

async function waitForVoices() {
    return new Promise((resolve) => {
        let voices = speechSynthesis.getVoices();
        if (voices.length) resolve(voices);
        else speechSynthesis.onvoiceschanged = () => { voices = speechSynthesis.getVoices(); resolve(voices); };
    });
}
async function initVoices() {
    if (voicesReady) return;
    await waitForVoices();
    const voices = speechSynthesis.getVoices();
    const preferred = ['Google 日本語', 'Microsoft Haruka', 'Microsoft Ichiro', 'Microsoft Sayaka', 'Kyoko'];
    selectedVoice = voices.find(v => preferred.includes(v.name)) || voices.find(v => v.lang.startsWith('ja')) || voices[0];
    voicesReady = true;
}
function updateSlider() {
    const slider = document.getElementById("seekSlider");
    slider.max = speechQueue.length - 1;
    slider.value = currentIndex;
    document.getElementById("progressLabel").innerText = `${currentIndex + 1} / ${speechQueue.length}`;
}
async function speakNext() {
    if (!isReading) return;
    if (currentIndex >= speechQueue.length) { isReading = false; document.getElementById("togglePlay").innerText = "▶️"; return; }
    await initVoices();
    const text = speechQueue[currentIndex]?.trim();
    if (!text) { currentIndex++; speakNext(); return; }
    updateSlider();
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.lang = 'ja-JP';
    if (selectedVoice) utterance.voice = selectedVoice;
    utterance.rate = 1; utterance.pitch = 1;
    utterance.onend = () => { currentIndex++; if (isReading) speakNext(); };
    utterance.onerror = () => {};
    isReading = true;
    document.getElementById("togglePlay").innerText = "⏸️";
    speechSynthesis.speak(utterance);
}
function togglePlayPause() {
    if (isReading) {
        speechSynthesis.cancel(); isReading = false; document.getElementById("togglePlay").innerText = "▶️";
    } else {
        isReading = true;
        if (!textExtracted) { startReading(); } else { speakNext(); }
    }
}
document.getElementById("seekSlider").addEventListener("input", (e) => {
    currentIndex = parseInt(e.target.value);
    updateSlider();
    if (isReading) { speechSynthesis.cancel(); setTimeout(() => { speakNext(); }, 100); }
});
async function startReading() {
    document.getElementById("togglePlay").innerText = "🔄";
    document.getElementById("togglePlay").disabled = true;
    speechSynthesis.cancel(); isReading = false;

    const visiblePages = $("#flipbook").paltau("view");
    const promises = visiblePages.map(async (pageNum) => {
        const pageSelector = `[page="${pageNum}"]`;
        const $page = $("#flipbook").find(pageSelector);
        const $img = $page.find("img").first();
        if ($img.length === 0) return "";
        try {
            const result = await Tesseract.recognize($($img)[0], "jpn", { tessedit_pageseg_mode: Tesseract.PSM.SINGLE_TEXT });
            return result.data.text.trim();
        } catch (err) {
            console.error(`OCR error on page ${pageNum}`, err);
            return "";
        }
    });

    const texts = await Promise.all(promises);
    const fullText = texts.filter(Boolean).join("\n");
    if (fullText) {
        speechQueue = fullText.split(/\r?\n/).map(s => s.trim().replace(/\s+/g, '')).filter(Boolean);
        currentIndex = 0; textExtracted = true; isReading = true; updateSlider();
        document.getElementById("togglePlay").disabled = false;
        document.getElementById("togglePlay").innerText = "⏸️";
        speakNext();
    } else {
        document.getElementById("togglePlay").disabled = true;
    }
    document.getElementById("togglePlay").disabled = false;
    document.getElementById("togglePlay").innerText = "▶️";
}
</script>

</html>
