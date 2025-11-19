<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">


  <script src="{{ asset('js/jquery/jquery.min.1.7.js') }}"></script>
  <script src="{{ asset('js/jquery/jquery-ui-1.8.20.custom.min.js') }}"></script>
  <script src="{{ asset('js/jquery/jquery.ui.touch-punch.min.js') }}"></script>
  <script src="{{ asset('js/lib/paltau.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/book/jquery.ui.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/book/jquery.ui.html4.css') }}" />

  <script>
    const pagesData = @json($pages);
    const sessionData = @json($sessionData);
    const BOOK_ID = "{{ $book_id }}";
    const FACE_PAGES = parseInt("{{ $pageNumberDetails[0] }}");
    const LAST_PAGE_NUMBER = parseInt("{{ $pageNumberDetails[1] }}");
  </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>

<body>

    <div id="flipbook">
      
        @forelse ($pages as $page)
            <div class="container {{ $pageNumberDetails[2] ? 'scale-up' : '' }} ">
                <img loading="lazy" src="{{ asset($page->page_image) }}" />
            </div>
        @empty
        @endforelse
      <div class="container">
            <div class="lock-message">
                <h3>フルアクセスのロックを解除</h3>
                <p>
                    現在プレビュー版をご覧いただいています。書籍全文をお読みいただくには、フルバージョンをご購入ください。
                    ご購入後、フルバージョンはライブラリで無制限にご利用いただけます。
                </p>
                <a  href="#" onclick="window.close()">戻る</a>
            </div>
        </div>
    </div>

    <div id="pagination" class="pagination-container hidden">
    <div class="pagination-desktop">
      <div class="pagination-info">
        <p>
          <span id="currentPage" class="font-medium">1</span>/
          <span id="pages" class="font-medium">-</span> ページ
        </p>
      </div>

      <div class="pagination-nav">
       

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
</body>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", async function() {
        async function loadBook(bookId) {
            return await sessionData || {
                bookId,
                currentPage: 1,
                bookmarks: [],
            };
        }

    const bookId = "{{ $book_id }}";

    let currentBook = await loadBook(bookId);

    let display = window.innerWidth > 992 ? "double" : "single";
    let latestBackPage = null;
    const backFacePageNumbers = new Set();
    function setPageName(page) {
      // Handle face pages
      if (page <= FACE_PAGES) {
        return `F${page}`;
      }

      // Handle back pages
      if (page > LAST_PAGE_NUMBER + FACE_PAGES) {

        backFacePageNumbers.add(parseInt(page))
        backFacePageNumbersArr = [...backFacePageNumbers]

        return `B${backFacePageNumbersArr.sort((a, b) => a - b).indexOf(parseInt(page)) + 1}`;
      }

      // Normal pages
      return page - FACE_PAGES;
    }
        // Initialize flipbook with saved current page
        $("#flipbook").paltau({
            height: "100%",
            width: "100%",
            display: display,
            autoCenter: true,
            acceleration: false,
            gradients: true,
            page: currentBook.currentPage || 1,
            elevation: 100,
            duration: 1000,
            when: {
                missing: function(event, pages) {
                    console.warn(`Missing pages detected:`, pages);
                }
            }
        });

    const totalPages = $("#flipbook").paltau("pages");
    document.getElementById("pages").innerText = totalPages;
    const pageWhileLoaded = $("#flipbook").paltau("page");
    document.getElementById("currentPage").innerText = setPageName(pageWhileLoaded);



    // Populate page selector dropdown
    const select = document.getElementById("pageInput");
    for (let i = 1 - FACE_PAGES; i <= (totalPages - FACE_PAGES); i++) {
      const option = document.createElement("option");
      option.value = i + FACE_PAGES;
      option.textContent = `${setPageName(i + FACE_PAGES)} ページ`;
      select.appendChild(option);
    }
    document.getElementById("pageInput").value = pageWhileLoaded;



    // Update on page paltau: save state + lazy load
    $("#flipbook").bind("turned", async function (event, page) {
      currentBook.currentPage = page;
    

      document.getElementById("currentPage").innerText = setPageName(page);
      document.getElementById("pageInput").value = page;

      refreshSelect(page);
     
      $('#slider').slider('value', page);

     $("#flipbook").paltau("page", page);


    });
        document.getElementById("currentPage").innerText = setPageName(1);
        window.addEventListener("orientationchange", updateFlipbookDisplay);
        window.addEventListener("resize", updateFlipbookDisplay);
        updateFlipbookDisplay();

        function updateFlipbookDisplay($event) {
            const isPortrait = window.innerHeight > window.innerWidth;
            const displayMode = isPortrait ? "single" : "double";

            $("#flipbook").paltau("display", displayMode);
            $("#flipbook").height("100%");
            $("#flipbook").width("100%");
        }

        swipeEvent(document.getElementById("flipbook"));

           // Slider initialization
    $("#slider").slider({
      min: 1,
      step: 1,
      max: totalPages,
      start: function (event, ui) {
        $(".thumbnail").remove();
        _thumbPreview = $('<div />', {
          class: "thumbnail"
        }).html(`<div style="width:90px">${setPageName(ui.value)} ページ</div>`);
        _thumbPreview.appendTo($(ui.handle));
      },
      slide: function (event, ui) {
        $(".thumbnail").remove();
        _thumbPreview = $('<div />', {
          class: "thumbnail"
        }).html(`<div style="width:90px">${setPageName(ui.value)} ページ</div>`);
        _thumbPreview.appendTo($(ui.handle));
      },
      stop: function (event, ui) {
        $("#flipbook").paltau("page", ui.value);
        $('#slider').slider('value', ui.value);
      },
    });
    $('#slider').slider('value', pageWhileLoaded);

        document.body.addEventListener(
            "keydown",
            (evt) => {
                if (evt.key == "ArrowRight") {
                    $("#flipbook").paltau("next");
                }
                if (evt.key == "ArrowLeft") {
                    $("#flipbook").paltau("previous");
                }
            },
            false
        );


    document.getElementById("pageInput").addEventListener("change", async function () {
      const selectedPage = parseInt($(this).val());
      $("#flipbook").paltau("page", selectedPage);
    });
    
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

            function isZoomed() {
                if (window.visualViewport) {
                    return window.visualViewport.scale !== 1;
                }
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

                // paltau page
                if (direction === "left") {
                    $("#flipbook").paltau("next");
                } else if (direction === "right") {
                    $("#flipbook").paltau("previous");
                }

                // Fade in
                setTimeout(() => {
                    viewer.classList.remove("opacity-0");
                    viewer.classList.add("opacity-100");
                }, 100);
            }
        }

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
        let translateX = 0,
            translateY = 0;

        // Update transform
        function applyTransform() {
            page.style.transform =
                `scale(${scale}) translate(${translateX / scale}px, ${translateY / scale}px)`;
        }

        // Wheel zoom
        page.addEventListener('wheel', (e) => {
            e.preventDefault();

            const rect = page.getBoundingClientRect();
            const offsetX = e.clientX - rect.left;
            const offsetY = e.clientY - rect.top;
            const percentX = (offsetX / rect.width) * 100;
            const percentY = (offsetY / rect.height) * 100;

            page.style.transformOrigin = `${percentX}% ${percentY}%`;

            if (e.deltaY < 0) {
                scale += scaleStep;
            } else {
                scale -= scaleStep;
            }

            scale = Math.min(Math.max(scale, minScale), maxScale);
            applyTransform();
        });

        // Mouse move updates transform-origin (for dynamic zoom point)
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

        // Drag to pan
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

        // Optional: double-click to reset zoom and position
        page.addEventListener('click', () => {
            scale = 1;
            translateX = 0;
            translateY = 0;
            page.style.transformOrigin = 'center';
            applyTransform();

        });
    });

     document.getElementById("flipbook").addEventListener("dblclick", function () {
      const controls = document.getElementById("pagination");
      controls.classList.toggle("hidden");
    
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
</script>

</html>
