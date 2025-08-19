<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/extras/jquery.min.1.7.js') }}"></script>
    <script src="{{ asset('js/extras/jquery-ui-1.8.20.custom.min.js') }}"></script>



    <script src="{{ asset('js/lib/turn.min.js') }}"></script>
    <script src="{{ asset('js/lib/turn.turn.html4.min.js') }}"></script>

    <!-- <script src="{{ asset('js/turn.js') }}"></script> -->

    <script src="{{ asset('js/tesseract.min.js') }}"></script>

    <script src="{{ asset('js/extras/modernizr.2.5.3.min.js') }}"></script>
    <script src="{{ asset('js/magazine.js') }}"></script>

    <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>



    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/magazine.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/jquery.ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/jquery.ui.html4.css') }}" />


    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>

<body>
 
    <div id="flipbook">
        @forelse ($pages as $page)
            <div class="container ">
                <img loading="lazy" src="{{ asset($page->page_image) }}" />
            </div>
        @empty
        @endforelse

    </div>

</body>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", async function() {

        const sessionData = @json($sessionData);
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

        // Initialize flipbook with saved current page
        $("#flipbook").turn({
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
</script>

</html>
