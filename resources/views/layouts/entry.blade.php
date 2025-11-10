<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/swiper/swiper.bundle.min.css') }}">

<link rel="manifest" href="/manifest.webmanifest">
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/service-worker.js');
    });
  }
</script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/swiper/swiper.bundle.min.js') }}"></script>
    <script>
        function cartCountdisplay(cartCount) {
            cartCountDom = document.getElementById("cart-count");
            if (cartCountDom) {
                if (cartCount > 0) {
                    cartCountDom.innerText = cartCount;
                    cartCountDom.classList.remove("hidden");
                } else {
                    cartCountDom.classList.add("hidden");
                }
            }
        }

        function loggedInDevicesCount(count) {
            let loggedInDevices = document.getElementById("loggedInDevices")

            if (loggedInDevices) {
                loggedInDevices.innerText = `{{ __('home.slotInfo') }}`.replace('{0}', count).replace('{2}', 2 - count)
            }
        }
    </script>

</head>

<body>
    <div class="flex flex-col gap-10">

        <div
            class="flex items-center justify-between px-7 py-3 sticky top-0 border-b bg-gray-900 text-gray-200 z-[1111] h-[64px]">
            <div class="flex items-center gap-4">
                <a href="/" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-gray-200">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span
                        class="text-xl tracking-wide text-gray-200 hidden sm:block">{{ __('home.digital_book') }}</span>

                </a>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative inline-block text-left">
                    <button id="langToggleBtn"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-300 text-sm font-medium rounded-md hover:text-gray-100 focus:outline-none">
                        🌐 {{ strtoupper(app()->getLocale()) }}
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="langDropdown"
                        class="absolute right-0 mt-2 w-40 bg-white border rounded shadow hidden z-[111111]">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English - 英語</a>
                        <a href="{{ route('lang.switch', 'jp') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Japanese - 日本語</a>
                    </div>
                </div>

                @if (Auth::check())
                    <a href="#" onclick="renderCartFromApi();openSidebarForCart()" class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <span
                            class="absolute bg-red-500 rounded-full w-5 h-5 flex justify-center items-center -top-[14px] -right-[8px] hidden"
                            id="cart-count"></span>
                    </a>
                    <a href="/library" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>

                        <!-- <span class="text-xs mt-1">Library</span> -->
                    </a>


                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
                                class="w-8 h-8 rounded-full" alt="User avatar">
                            <span class="hidden">{{ auth()->user()->name }}</span>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg z-50 p-4"
                            style="display: none;">
                            <div class="mb-2">
                                <p class="font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100 rounded">
                                    {{ __('home.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="#" onclick="openSidebarForCart()" class="flex items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <span
                            class="absolute bg-red-500 rounded-full w-5 h-5 flex justify-center items-center -top-[14px] -right-[8px] hidden"
                            id="guest-cart-count"></span>
                    </a>
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode('Guest') }}"
                                class="w-8 h-8 rounded-full" alt="User avatar">
                            <span class="hidden md:inline"> {{ __('home.guest') }}</span>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-64 sm:w-96 bg-white border border-gray-200 rounded-md shadow-lg z-50 p-4"
                            style="display: none;">
                            <div class="bg-white shadow-sm max-w-sm mx-auto space-y-4">
                                <div class="flex items-center justify-between border-b pb-3">
                                    <div>
                                        <p class="text-gray-700 font-bold"> {{ __('home.helloGuest') }}</p>
                                        <p class="text-sm text-gray-500"> {{ __('home.signInAndCreateAccount') }}</p>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('register') }}"
                                        class="flex-1 inline-block text-center px-4 py-2 bg-yellow-400 text-black font-bold rounded hover:bg-yellow-500 transition">
                                        {{ __('home.register') }}
                                    </a>

                                    <a href="{{ route('login') }}"
                                        class="flex-1 inline-block text-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300 transition">
                                        {{ __('home.signin') }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

            </div>
            @if (Auth::check())
                <div class="absolute flex gap-1 text-sm text-white right-0 bottom-[-20px] bg-red-500">
                    <span class="font-semibold text-red px-2" id="loggedInDevices"></span>
                </div>
            @endif
        </div>

        <div class="flex flex-col justify-center items-center">
            <div class="container">
                <div class="flex flex-col gap-12 p-8">

                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>



    <!-- Sidebar -->
    <!-- Sidebar Cart -->
    <div id="sidebarCart"
        class="fixed top-0 right-0 h-full w-56 bg-slate-50 shadow-xl border-l border-slate-200 
            transform translate-x-full transition-transform duration-300 py-20 z-50 flex flex-col justify-between">

        <!-- Header -->
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg text-gray-700 font-semibold">{{ __('home.cart') }}</h2>
            <button onclick="closeSidebarOfCart()" class="text-gray-600 hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div id="cart-items" class="p-4 space-y-4 overflow-y-auto flex-1 mb-10">
            <!-- Dynamic cart items will be injected here -->
        </div>

        <!-- Subtotal & Checkout -->
        <div id="subTotalSection" class="p-4 border-t hidden fixed w-full bottom-0 z-[1111] bg-slate-50">
            <div class="flex justify-between mb-4">
                <span class="text-gray-800 font-semibold"> {{ __('home.total') }}</span>
                <span id="cart-total" class="text-gray-800 font-bold">¥0</span>
            </div>
            <button onclick="proceedToBuy()" id="proceedToBuy"
                class="w-full text-center py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded">
                {{ __('home.proceedToBuy') }}
            </button>
        </div>

    </div>


    <!-- Toast Container -->
    <div id="infoToast" class="fixed bottom-5 right-5 z-[111111] hidden">
        <div
            class="bg-white border-l-4 border-blue-600 text-gray-800 px-4 py-3 shadow-lg rounded-md flex items-start space-x-3 max-w-xs">
            <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a7 7 0 107 7H9V2z" />
                <path d="M13 13H7v2h6v-2z" />
            </svg>
            <div>
                <p id="toastMessage" class="text-sm font-medium">This is your message</p>
            </div>
        </div>
    </div>

    <!-- Backdrop -->
    <div id="authModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <!-- Modal Box -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Sign in required</h3>
                    <p class="text-gray-600 text-sm mt-1">You need an account to purchase items.</p>
                </div>
                <button onclick="closeAuthModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex gap-4 pt-2">
                <a href="{{ route('register') }}"
                    class="flex-1 inline-block text-center px-4 py-2 bg-yellow-400 text-black font-bold rounded hover:bg-yellow-500 transition">
                    Register
                </a>
                <a href="{{ route('login') }}"
                    class="flex-1 inline-block text-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300 transition">
                    Sign In
                </a>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Confirm Purchase</h2>
            <p>Are you sure you want to proceed to buy?</p>
            <div class="mt-6 flex justify-center gap-4">
                <button onclick="confirmProceed()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Yes
                </button>
                <button onclick="closeBuyModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>

</body>
<script>
    const langBtn = document.getElementById('langToggleBtn');
    const langDropdown = document.getElementById('langDropdown');

    langBtn.addEventListener('click', () => {
        langDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!langBtn.contains(e.target) && !langDropdown.contains(e.target)) {
            langDropdown.classList.add('hidden');
        }
    });
</script>
<script>
    function showToast(message, duration = 3000) {
        const toast = document.getElementById('infoToast');
        const toastMessage = document.getElementById('toastMessage');

        toastMessage.textContent = message;
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, duration);
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("form").forEach(function(form) {
            form.addEventListener("submit", function(e) {
                const submitBtn = form.querySelector("button[type='submit']");
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        ' <span class="ml-2">{{ __('loading') }}</span>';
                }
            });
        });
    });
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
  <div class="flex flex-col w-full gap-4 relative border border-gray-200 shadow-sm  p-4 bg-white hover:shadow-md transition">

    <!-- Book Image -->
    <a href="/detail/${item?.id}/view" class="block w-full h-[180px] overflow-hidden ">
      <img 
        loading="lazy"
        src="/${item.images || 'placeholder.jpg'}"
        alt="Product Image"
        class="w-full h-full"
        onerror="this.onerror=null;this.src='/placeholder.jpg';"
      />
    </a>

    <!-- Info -->
    <div class="flex flex-col justify-between gap-2">

      <!-- Price -->
      <p class="text-gray-800 font-semibold text-sm">${ new Intl.NumberFormat("ja-JP", { style: "currency", currency: "JPY" }).format(
   (item.price * item.qty).toFixed(0)
  )}</p>

      <!-- Quantity Controls -->
      <div class="flex items-center gap-2">
        <button onclick="updateQty(${index}, -1)" 
                class="p-1 border rounded hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
          </svg>
        </button>
        <span class="text-gray-700 text-sm w-6 text-center">${item.qty}</span>
        <button onclick="updateQty(${index}, 1)" 
                class="p-1 border rounded hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Delete Button -->
    <button onclick="removeFromCart(${index})"
            class="absolute top-2 right-2 p-1 text-gray-600 hover:text-red-600 bg-gray-100 rounded-full shadow-sm">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none"
           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 
              19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 
              5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 
              .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 
              1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 
              51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 
              2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
      </svg>
    </button>
  </div>
`;

            container.appendChild(itemDiv);
            total += item.price * item.qty;
        });

        // Update total at bottom
        document.getElementById('cart-total').innerText = `${ new Intl.NumberFormat("ja-JP", { style: "currency", currency: "JPY" }).format(
   total.toFixed(0)
  )}`;

        if (cart.length > 0) {
            if (document.getElementById('guest-cart-count')) {
                document.getElementById('guest-cart-count').innerText = cart.length;
                document.getElementById('guest-cart-count').classList.remove("hidden")
                document.getElementById("subTotalSection").classList.remove("hidden")
            }

        } else {
            if (document.getElementById('guest-cart-count')) {
                document.getElementById('guest-cart-count').classList.add("hidden")
                document.getElementById("subTotalSection").classList.add("hidden")
            }
            container.innerHTML = `
    <div class="flex flex-col items-center justify-center text-center py-10 text-gray-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6h11L17 13M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/>
      </svg>
      <p class="font-semibold text-lg">{{ __('home.yourCartIsEmpty') }}</p>
      <p class="text-sm mt-1">{{ __('home.emptyCartInfo') }}</p>
    </div>
  `;
        }

    }

    let payloadOfCart = [];
    async function renderCartFromApi() {

        const container = document.getElementById('cart-items');
        container.innerHTML = `
  <div class="flex flex-col items-center justify-center py-10 text-gray-500 animate-pulse">
    <svg class="w-12 h-12 mb-4 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
    <p class="font-semibold text-lg">Loading your cart...</p>
  </div>
`;

        try {
            const response = await fetch('/cart'); // Replace with your actual API URL if needed
            if (!response.ok) throw new Error('Failed to fetch cart data.');

            const data = await response.json();
            const cart = data.cartList || [];
            let total = 0;

            if (cart.length === 0) {
                container.innerHTML = `
        <div class="flex flex-col items-center justify-center text-center py-10 text-gray-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6h11L17 13M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/>
          </svg>
          <p class="font-semibold text-lg">{{ __('home.yourCartIsEmpty') }}</p>
          <p class="text-sm mt-1">{{ __('home.emptyCartInfo') }}</p>
        </div>
      `;
                document.getElementById('cart-count').classList.add("hidden");
                document.getElementById("subTotalSection").classList.add("hidden");
                document.getElementById('cart-total').innerText = '¥0.00';
                return;
            }


            container.innerHTML = "";
            payloadOfCart = [];
            cart.forEach((item, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex flex-col items-center gap-4';

                itemDiv.innerHTML = `
  <div class="flex flex-col w-full gap-4 relative border border-gray-200 shadow-sm  p-4 bg-white hover:shadow-md transition">
    
    <!-- Book Image -->
    <a href="/detail/${item?.book?.id}/view" class="block w-full h-[180px] overflow-hidden">
      <img 
        loading="lazy"
        src="/${item.book?.images || 'placeholder.jpg'}"
        alt="Product Image"
        class="w-full h-full "
        onerror="this.onerror=null;this.src='/placeholder.jpg';"
      />
    </a>

    <!-- Info -->
    <div class="flex flex-col justify-between gap-2">
      
      <!-- Price -->
      <p class="text-gray-800 font-semibold text-sm">${new Intl.NumberFormat("ja-JP", { style: "currency", currency: "JPY" }).format(
  (item.book?.price * item.quantity).toFixed(0)
  )}</p>

      <!-- Quantity Controls -->
      <div class="flex items-center gap-2">
        <button onclick="updateQuantity(${item.book.id}, ${item.quantity} - 1)" 
                class="p-1 border rounded hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
          </svg>
        </button>
        <span class="text-gray-700 text-sm w-6 text-center">${item.quantity}</span>
        <button onclick="updateQuantity(${item.book.id}, ${item.quantity} + 1)" 
                class="p-1 border rounded hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Delete Button -->
    <button onclick="if(confirm('この本をカートから削除します。')) deleteCart(${item.book.id})"
            class="absolute top-2 right-2 p-1 text-gray-600 hover:text-red-600 bg-gray-100 rounded-full shadow-sm">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none"
           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 
              19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 
              5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 
              .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 
              1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 
              51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 
              2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
      </svg>
    </button>
  </div>
`;


                container.appendChild(itemDiv);
                total += item.book?.price * item.quantity;

                payloadOfCart.push({
                    book_id: item.book.id,
                    quantity: item.quantity,
                    price: parseFloat(item.book.price * item.quantity),
                    per_price: parseFloat(item.book.price)
                });
            });

            document.getElementById('cart-total').innerText = `${new Intl.NumberFormat("ja-JP", { style: "currency", currency: "JPY" }).format(
  total.toFixed(0)
  )}`;
            document.getElementById('cart-count').innerText = cart.length;
            document.getElementById('cart-count').classList.remove("hidden");
            document.getElementById("subTotalSection").classList.remove("hidden");

        } catch (error) {
            console.error('Error rendering cart:', error);
            container.innerHTML = `
      <div class="text-red-500 text-center py-10">
        Failed to load cart. Please try again later.
      </div>
    `;
        }
    }

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
        if (document.getElementById('sidebarCart')) {
            document.getElementById('sidebarCart').classList.remove('translate-x-full');
        }
        if (document.getElementById('overlayCart')) {
            document.getElementById('overlayCart').classList.remove('hidden');
        }
    }

    function closeSidebarOfCart() {
        if (document.getElementById('sidebarCart')) {
            document.getElementById('sidebarCart').classList.add('translate-x-full');
        }
        if (document.getElementById('overlayCart')) {
            document.getElementById('overlayCart').classList.add('hidden');
        }

    }
</script>

<script>
    function addToCart(button, book, quantity = 1) {
        const isLoggedIn = @json(Auth::check());
        
        // const isEmailVerified = isLoggedIn ? @json(Auth::check() && Auth::user()->hasVerifiedEmail()) : false;
        const isEmailVerified = true // no need email verification

        if (isLoggedIn) {
            if (isEmailVerified) {

                const textSpan = button.querySelector('.button-text');
                const loadingSpan = button.querySelector('.loading');
                button.setAttribute("disabled", true)
                textSpan.classList.add('hidden');
                loadingSpan.classList.remove('hidden');


                fetch('/cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            book_id: book?.id,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // showToast(data.message);
                            cartCountdisplay(data.cartCount)
                            renderCartFromApi()
                            openSidebarForCart()

                        } else {
                            showToast(data.message);
                        }

                        textSpan.classList.remove('hidden');
                        loadingSpan.classList.add('hidden');
                        button.removeAttribute("disabled")

                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                showToast("'Please verify your email before adding items to the cart.");
            }
        } else {
            addGuestItemToCart(book)
             openSidebarForCart()
        }

    }

    function updateQuantity(bookId, quantity = 0) {
        if (quantity == 0) return;
        fetch('/cart/update-quantity', {
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
                console.log("success")
                renderCartFromApi()

            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function deleteCart(bookId) {
        fetch('/cart/delete-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    book_id: bookId
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("success")
                renderCartFromApi()
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
<script>
    function openAuthModal() {
        document.getElementById('authModal').classList.remove('hidden');
    }

    function closeAuthModal() {
        document.getElementById('authModal').classList.add('hidden');
    }
</script>
<script>
    function proceedToBuy() {
        const isLoggedIn = @json(Auth::check());
        const isEmailVerified = isLoggedIn ? @json(Auth::check() && Auth::user()->hasVerifiedEmail()) : false;

        if (isLoggedIn) {
            window.location.href = "/cart-web"
        } else {

            window.location.href = "/login-register"
        }
    }

    function confirmToBuy() {
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeBuyModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }


    function confirmProceed() {
        closeBuyModal();
        document.getElementById("proceedToBuy").setAttribute("disabled", true);
        document.getElementById("proceedToBuy").innerText = "{{ __('home.loading') }}"
        fetch('/purchases', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')


                },
                body: JSON.stringify({
                    books: payloadOfCart
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                window.location.href = `/purchases?purchase_id=${data?.purchase_id}`;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById("proceedToBuy").removeAttribute("disabled");
                document.getElementById("proceedToBuy").innerText = "Proceed to Buy"
            });
    }
</script>


</html>
