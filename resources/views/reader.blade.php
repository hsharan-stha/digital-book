<html>

<head>
    <script src="{{ asset('js/extras/jquery.min.1.7.js') }}"></script>
    <script src="{{ asset('js/extras/jquery-ui-1.8.20.custom.min.js') }}"></script>



    <script src="{{ asset('js/lib/turn.min.js') }}"></script>
    <script src="{{ asset('js/lib/turn.turn.html4.min.js') }}"></script>

    <!-- <script src="{{ asset('js/turn.js') }}"></script> -->

    <!-- <script src="{{ asset('js/tesseract.min.js') }}"></script> -->

    <script src="{{ asset('js/extras/modernizr.2.5.3.min.js') }}"></script>
    <script src="{{ asset('js/magazine.js') }}"></script>

    <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('js/dexie.min.js') }}"></script>


    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/magazine.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/jquery.ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/jquery.ui.html4.css') }}" />


    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>

<body>
    <div id="bookmark" class="hidden">
        <div id="unmark" class="hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
            </svg>
        </div>
        <div id="mark" class="hidden">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd"
                    d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    <div id="flipbook">
        @forelse ($pages as $page)
            <div class="container ">
                <img loading="lazy" src="{{ asset($page->page_image) }}" />
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

                    Showing <span id="currentPage" class="font-medium">1</span> of
                    <span id="pages" class="font-medium">97</span> results
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
            <div id="slider-bar" class="turnjs-slider">
                <div id="slider"></div>
            </div>
        </div>
    </div>

    <!-- Hover Zone (small invisible strip on left edge) -->
    <!-- <div id="hover-zone" class="hover-zone"></div> -->

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="toc-wrapper">
            <h3 class="toc-title">BookMarks</h3>
            <ul id="toc" class="toc-list"></ul>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
</body>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", async function() {
        // Initialize Dexie DB
        const db = new Dexie("ReaderDatabase");
        db.version(1).stores({
            books: "bookId", // primary key
        });

        // Helper to load book data (page, bookmarks)
        async function loadBook(bookId) {
            return (await db.books.get(bookId)) || {
                bookId,
                currentPage: 1,
                bookmarks: [],
            };
        }

        // Helper to save book data
        async function saveBook(bookData) {
            await db.books.put(bookData);
        }

        // Assume bookId is fixed or derive from URL, etc.
        // Replace this with your actual book identifier logic
        const bookId = "book-{{ $book_id }}";

        // Load book state from IndexedDB
        let currentBook = await loadBook(bookId);

        let display = window.innerWidth > 992 ? "double" : "single";

        // Initialize flipbook with saved current page
        $("#flipbook").turn({
            height: "100%",
            width: "100%",
            display: display,
            autoCenter: true,
            acceleration: true,
            gradients: true,
            page: currentBook.currentPage || 1,
        });

        const totalPages = $("#flipbook").turn("pages");
        document.getElementById("pages").innerText = totalPages;
        const pageWhileLoaded = $("#flipbook").turn("page");
        document.getElementById("currentPage").innerText = pageWhileLoaded;

        // Populate page selector dropdown
        const select = document.getElementById("pageInput");
        for (let i = 1; i <= totalPages; i++) {
            const option = document.createElement("option");
            option.value = i;
            option.textContent = `Page ${i}`;
            select.appendChild(option);
        }
        document.getElementById("pageInput").value = pageWhileLoaded;

        // Update flipbook on page turn and save state
        $("#flipbook").bind("turned", async function(event, page) {
            currentBook.currentPage = page;
            await saveBook(currentBook);

            document.getElementById("currentPage").innerText = page;
            document.getElementById("pageInput").value = page;

            refreshSelect(page);
            toggleBookmark();
            $('#slider').slider('value', page);
        });

        window.addEventListener("orientationchange", updateFlipbookDisplay);
        window.addEventListener("resize", updateFlipbookDisplay);
        updateFlipbookDisplay();

        function updateFlipbookDisplay($event) {
            const isPortrait = window.innerHeight > window.innerWidth;
            const displayMode = isPortrait ? "single" : "double";

            $("#flipbook").turn("display", displayMode);
            $("#flipbook").height("100%");
            $("#flipbook").width("100%");
        }

        swipeEvent(document.getElementById("flipbook"));

        document.body.addEventListener(
            "keydown",
            (evt) => {
                if (evt.key == "ArrowRight") {
                    $("#flipbook").turn("next");
                }
                if (evt.key == "ArrowLeft") {
                    $("#flipbook").turn("previous");
                }
            },
            false
        );

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
                if (evt.touches.length === 2) {
                    pinchDetected = true;
                }
            }

            function handleTouchEnd(evt) {
                if (pinchDetected) return;

                if (!xDown || !yDown) return;

                const xUp = evt.changedTouches[0].clientX;
                const yUp = evt.changedTouches[0].clientY;

                const dx = xUp - xDown;
                const dy = yUp - yDown;

                if (Math.abs(dx) > Math.abs(dy)) {
                    if (dx > 50) {
                        fadeAndTurnPage("right");
                    } else if (dx < -50) {
                        fadeAndTurnPage("left");
                    }
                }

                xDown = null;
                yDown = null;
            }

            function fadeAndTurnPage(direction) {
                const viewer = document.getElementById("flipbook");

                // Fade out
                viewer.classList.remove("opacity-100");
                viewer.classList.add("opacity-0");

                // Turn page
                if (direction === "left") {
                    $("#flipbook").turn("next");
                } else if (direction === "right") {
                    $("#flipbook").turn("previous");
                }

                // Fade in
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
            $("#flipbook").turn("page", selectedPage);
        });

        document.getElementById("flipbook").addEventListener("dblclick", function() {
            const controls = document.getElementById("pagination");
            const bookmarks = document.getElementById("bookmark");

            controls.classList.toggle("hidden");
            bookmarks.classList.toggle("hidden");

            toggleBookmark();
        });

        document.getElementById("backPage").addEventListener("click", async function() {
            if (currentBook.currentPageHistory) {
                $("#flipbook").turn("page", currentBook.currentPageHistory);
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
                li.textContent = "No data to display";
                tocList.appendChild(li);
            } else {
                bookmarksArray.forEach((item) => {
                    const li = document.createElement("li");
                    li.style.display = "flex";
                    li.style.alignItems = "center";
                    li.style.gap = "8px";
                    li.style.marginBottom = "8px";
                    li.className = "toc-item";

                    const img = document.createElement("img");
                    img.src = item?.detail;
                    img.alt = `Thumbnail for page ${item?.page}`;
                    img.style.width = "32px";
                    img.style.height = "32px";
                    img.style.borderRadius = "50%";
                    img.style.objectFit = "cover";

                    const span = document.createElement("span");
                    span.className = "detail";
                    span.textContent = `Page ${item?.page}`;

                    const svgBookmark = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#4A5568" viewBox="0 0 24 24">
              <path d="M6 2C4.895 2 4 2.895 4 4v18l8-4 8 4V4c0-1.105-.895-2-2-2H6z"/>
            </svg>`;

                    const iconWrapper = document.createElement("span");
                    iconWrapper.innerHTML = svgBookmark;

                    li.appendChild(img);
                    li.appendChild(span);
                    li.appendChild(iconWrapper);

                    li.onclick = () => {
                        $("#flipbook").turn("page", item?.page);
                    };

                    tocList.appendChild(li);
                });
            }
        }

        function toggleBookmark() {
            const bookmarks = document.getElementById("bookmark");
            if (isBookmark()) {
                bookmarks.lastElementChild.classList.remove("hidden");
                bookmarks.firstElementChild.classList.add("hidden");
            } else {
                bookmarks.firstElementChild.classList.remove("hidden");
                bookmarks.lastElementChild.classList.add("hidden");
            }
        }

        function isBookmark() {
            const currentPage = currentBook.currentPage.toString();
            return bookmarksArray.some((b) => b.page.toString() === currentPage);
        }

        // Bookmark buttons
        document.getElementById("mark").addEventListener("click", async function($event) {
            const currentPage = currentBook.currentPage.toString();
            bookmarksArray = bookmarksArray.filter((b) => b.page.toString() !== currentPage);
            currentBook.bookmarks = bookmarksArray;
            await saveBook(currentBook);

            toggleBookmark();
            loadBookMark();
        });

        document.getElementById("unmark").addEventListener("click", async function($event) {
            const currentPage = currentBook.currentPage.toString();
            const pageSelector = `[page="${currentPage}"]`;
            const $page = $("#flipbook").find(pageSelector);
            const $img = $page.find("img").first();

            if (!bookmarksArray.some((b) => b.page.toString() === currentPage)) {
                bookmarksArray.push({
                    page: currentPage,
                    detail: $img.attr("src"),
                });
                currentBook.bookmarks = bookmarksArray;
                await saveBook(currentBook);
            }

            toggleBookmark();
            loadBookMark();
        });

        loadBookMark();
        toggleBookmark();

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
            if (!sidebar.contains(event.target) && !hoverZone.contains(event.target)) {
                closeSidebar();
            }
        });

        // Slider initialization
        $("#slider").slider({
            min: 1,
            step: 1,
            max: totalPages,
            slide: function(event, ui) {
                $(".thumbnail").remove();
                _thumbPreview = $('<div />', {
                    class: "thumbnail",
                }).html(`<div>Page: ${ui.value}</div>`);
                _thumbPreview.appendTo($(ui.handle));
            },
            stop: function(event, ui) {
                $("#flipbook").turn("page", ui.value);
                $('#slider').slider('value', ui.value);
            },
        });
        $('#slider').slider('value', pageWhileLoaded);
    });
</script>

</html>
