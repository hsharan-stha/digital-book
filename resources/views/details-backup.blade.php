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

        /* Overlay styling */
        .cart-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        /* Sidebar base */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 14rem;
            /* 56 in Tailwind = 14rem */
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 50;
            display: flex;
            flex-direction: column;
            padding-top: 5rem;
            /* py-20 = 5rem top padding */
        }

        /* Header inside sidebar */
        .cart-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            /* gray-200 */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-title {
            font-size: 1.125rem;
            /* text-lg */
            color: #6b7280;
            /* gray-500 */
        }

        .cart-close-btn {
            background: none;
            border: none;
            cursor: pointer;
        }

        .cart-close-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Cart body flex layout */
        .cart-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        /* Cart items container */
        .cart-items {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            overflow-y: auto;
            flex-grow: 1;
        }

        /* Footer: subtotal & button */
        .cart-footer {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            /* gray-200 */
        }

        .cart-subtotal {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .cart-subtotal-label {
            color: #1f2937;
            /* gray-800 */
            font-weight: 600;
        }

        .cart-subtotal-value {
            color: #1f2937;
            font-weight: 700;
        }

        .cart-proceed-btn {
            width: 100%;
            background-color: #facc15;
            /* yellow-400 */
            color: black;
            font-weight: 700;
            text-align: center;
            padding: 0.75rem 0;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .cart-proceed-btn:hover {
            background-color: #eab308;
            /* yellow-500 */
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

            <button class="overlay-button" onclick="addToCart(this, {{ $bookDetails }}, 1)">
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

    <!-- Overlay -->
    <div id="overlayCart" class="cart-overlay hidden" onclick="closeSidebarOfCart()"></div>

    <!-- Sidebar -->
    <div id="sidebarCart" class="cart-sidebar">
        <div class="cart-header">
            <h2 class="cart-title">Cart</h2>
            <button onclick="closeSidebarOfCart()" class="cart-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="cart-close-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="cart-body">
            <!-- Cart Items List -->
            <div id="cart-items" class="cart-items"></div>

            <!-- Subtotal & Actions -->
            <div class="cart-footer">
                <div class="cart-subtotal">
                    <span class="cart-subtotal-label">Subtotal</span>
                    <span id="cart-total" class="cart-subtotal-value">$0.00</span>
                </div>
                <button class="cart-proceed-btn">Proceed to Buy</button>
            </div>
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
        function addToCart(button, book, quantity = 1) {

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
                            book_id: book?.id,
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
                // showToast("Please login before add to cart");
                addGuestItemToCart(book)
                openSidebarForCart()
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
    <script>
        const CART_KEY = 'cart_items';

        // Add to cart function
        function addGuestItemToCart(item) {
            item = {
                ...item,
                qty: 1
            }
            let cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];

            const existing = cart.find(i => i.id === item.id);
            if (existing) {
                showToast("Already added in cart");
                //  existing.qty += item.qty;
            } else {
                cart.push(item);
            }

            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            renderGuestCart();
        }

        // Render cart items
        function renderGuestCart() {
            const container = document.getElementById('cart-items');
            const cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];

            container.innerHTML = ''; // Clear previous

            let total = 0;
            cart.forEach((item, index) => {

                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex flex-col items-center gap-4';

                itemDiv.innerHTML = `
  <div class="flex items-center w-full gap-4 relative">
    <img loading="lazy" src="${item.images}" alt="Product Image" class="w-20 h-20 object-cover border rounded" />
    <div class="flex-1 flex flex-col">
      <p class="text-gray-800 font-semibold text-sm mb-1">¥${(item.price * item.qty).toFixed(2)}</p>
      <div class="flex items-center space-x-2">
        <button onclick="updateQty(${index}, -1)" class="p-1 border rounded hover:bg-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
          </svg>
        </button>
        <span class="text-gray-700 text-sm">${item.qty}</span>
        <button onclick="updateQty(${index}, 1)" class="p-1 border rounded hover:bg-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </div>
    </div>
    <button onclick="removeFromCart(${index})" class="absolute top-0 right-0 p-1 text-gray-400 hover:text-red-600">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>

    </button>
  </div>
`;

                container.appendChild(itemDiv);
                total += item.price * item.qty;
            });

            // Update total at bottom
            document.getElementById('cart-total').innerText = `¥${total.toFixed(2)}`;


        }

        // On page load, render cart
        window.addEventListener('load', renderGuestCart);

        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];
            cart.splice(index, 1); // Remove item by index
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            renderGuestCart(); // Re-render cart after removal
        }

        function updateQty(index, change) {
            let cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];
            if (!cart[index]) return;

            cart[index].qty += change;

            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }

            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            renderGuestCart();
        }
    </script>
    <script>
        function openSidebarForCart() {
            document.getElementById('overlayCart').classList.remove('hidden');
            document.getElementById('sidebarCart').style.transform = 'translateX(0)';
        }

        function closeSidebarOfCart() {
            document.getElementById('overlayCart').classList.add('hidden');
            document.getElementById('sidebarCart').style.transform = 'translateX(100%)';
        }
    </script>
