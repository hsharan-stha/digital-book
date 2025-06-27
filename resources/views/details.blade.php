    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('js/extras/jquery.min.1.7.js') }}"></script>
    <script src="{{ asset('js/extras/jquery-ui-1.8.20.custom.min.js') }}"></script>

    <script src="{{ asset('js/lib/turn.min.js') }}"></script>
    <script src="{{ asset('js/lib/turn.turn.html4.min.js') }}"></script>

    <script src="{{ asset('js/extras/modernizr.2.5.3.min.js') }}"></script>
    <script src="{{ asset('js/magazine.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/magazine.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/jquery.ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magazine/jquery.ui.html4.css') }}" />
    <style>
        .overlay {
            position: absolute;
            bottom: 0;
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            z-index: 50;
        }

        .overlay-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #fff;
        }

        .overlay-text {
            font-size: 1rem;
            line-height: 1.6;
            max-width: 500px;
            margin-bottom: 1.5rem;
            color: #f0f0f0;
        }

        .overlay-button {
            background-color: #16a34a;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 1.25rem;
        }

        .overlay-button:hover {
            background-color: #15803d;
        }

        .button-text {
            display: inline-block;
        }

        .loading {
            display: none;
            margin-left: 0.5rem;
        }

        .overlay-link {
            color: #ccc;
            font-size: 0.95rem;
            text-decoration: underline;
            transition: color 0.2s ease;
        }

        .overlay-link:hover {
            color: #ffffff;
        }


        .toast {
            position: fixed;
            bottom: 1.25rem;
            /* bottom-5 */
            right: 1.25rem;
            /* right-5 */
            z-index: 50;
            display: none;
            /* Hidden by default */
        }

        .toast-content {
            background-color: #ffffff;
            border-left: 4px solid #2563eb;
            /* blue-600 */
            color: #1f2937;
            /* gray-800 */
            padding: 0.75rem 1rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border-radius: 0.375rem;
            /* rounded-md */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            max-width: 20rem;
            /* max-w-xs */
        }

        .toast-icon {
            width: 1.5rem;
            /* w-6 */
            height: 1.5rem;
            /* h-6 */
            color: #2563eb;
            /* blue-600 */
            margin-top: 0.125rem;
            /* mt-0.5 */
            flex-shrink: 0;
        }

        .toast-text {
            font-size: 0.875rem;
            /* text-sm */
            font-weight: 500;
        }
    </style>
    <div>
        <div id="flipbook">
            <div class="container">
                <img loading="lazy" src="{{ asset($bookDetails->images) }}" alt="Page {{ 0 }}"
                    class="w-full h-full object-contain mx-auto">
            </div>
            @foreach ($bookDetails->pages as $page)
                <div class="container">
                    <img loading="lazy" src="{{ asset($page->page_image) }}" alt="Page {{ $loop->iteration }}"
                        class="w-full h-full object-contain mx-auto">
                </div>
            @endforeach

            <div class="container">
                <div class="overlay" style="inset: 0;bottom: unset; z-index: 1111; background-color: red;">
                    <h3 class="overlay-title">Unlock Full Access</h3>
                    <p class="overlay-text">You're viewing a preview. Purchase the full version to read the entire book.
                        Once purchased, the full version will be available in your library for unlimited access.
                    </p>


                </div>
            </div>


        </div>


        <div class="overlay">
            <h3 class="overlay-title">🔒 Unlock Full Access</h3>

            <p class="overlay-text">
                You're viewing a preview. Purchase the full version to read the entire book.<br>
                Once purchased, you can read it anytime from your <strong>Library</strong>.
            </p>

            <button class="overlay-button" onclick="addToCart(this, {{ $bookDetails->id }}, 1)">
                <span class="button-text">📚 Add to Cart Now – ¥{{ $bookDetails->price }}</span>
                <span class="loading hidden">Loading...</span>
            </button>

            <div style="display: felx;justify-content: space-between;">
                <a href="/" class="overlay-link">
                    ⬅️ Go to Home Page
                </a>
                <a href="/cart" class="overlay-link">
                    📚 Go to Cart Page
                </a>
            </div>
        </div>

    </div>


    <!-- Toast Container -->
    <div id="infoToast" class="toast hidden">
        <div class="toast-content">
            <svg class="toast-icon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a7 7 0 107 7H9V2z" />
                <path d="M13 13H7v2h6v-2z" />
            </svg>

            <p id="toastMessage" class="toast-text">This is your message</p>

        </div>
    </div>

    <!-- Turn.js Script -->

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let display = window.innerWidth > 992 ? "double" : "single";

            $("#flipbook").turn({
                height: "100%",
                width: "100%",
                display: display,
                autoCenter: true,
                acceleration: true,
                gradients: true,

                elevation: 100,
                duration: 1000,
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
        });
    </script>

    <script>
        function showToast(message, duration = 3000) {
            const toast = document.getElementById('infoToast');
            const msg = document.getElementById('toastMessage');
            msg.textContent = message;
            toast.classList.remove('hidden');
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, duration);
        }
    </script>



    <script>
        function addToCart(button, bookId, quantity = 1) {

            const isLoggedIn = "{{ Auth::check() }}";
            if (isLoggedIn) {

                const textSpan = button.querySelector('.button-text');
                const loadingSpan = button.querySelector('.loading');

                // Show loading state
                textSpan.classList.add('hidden');
                loadingSpan.classList.remove('hidden');

                fetch('/cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            book_id: bookId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data)
                        if (data.success) {
                            showToast(data.message);
                            cartCountdisplay(data.cartCount)
                            // Optionally redirect
                            // window.location.href = data.redirect;

                        } else {
                            showToast(data.message);
                        }

                        textSpan.classList.remove('hidden');
                        loadingSpan.classList.add('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                showToast("Please login before add to cart");
            }
        }
    </script>
    <script>
        swipeEvent(document.getElementById("flipbook"));

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
    </script>
