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
                    <span class="text-xl tracking-wide text-gray-200 xs:hidden">Digital Book</span>

                </a>
            </div>

            <div class="flex items-center space-x-4">

                @if (Auth::check())
                    <a href="{{ route('cart.index') }}" class="flex items-center relative">
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
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg z-50 p-4">
                            <div class="mb-2">
                                <p class="font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100 rounded">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode('Guest') }}"
                                class="w-8 h-8 rounded-full" alt="User avatar">
                            <span class="hidden md:inline"> Guest</span>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-md shadow-lg z-50 p-4">
                            <div class="mb-2">
                                <p class="font-semibold text-gray-700">Guest</p>
                                <p class="text-sm text-gray-500">Anonymous</p>
                            </div>


                            <div class="flex gap-4">
                                <a href="{{ route('register') }}"
                                    class="w-full inline-block text-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition duration-200">
                                    Register
                                </a>

                                <a href="{{ route('login') }}"
                                    class="w-full inline-block text-center px-4 py-2 bg-gray-100 text-gray-600 font-semibold rounded-md shadow hover:bg-gray-200 transition duration-200">
                                    Login
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <div class="flex flex-col justify-center items-center">
            <div class="container">
                <div class="flex flex-col gap-12 p-8">

                    {{ $slot }}
                </div>
            </div>
        </div>







    </div>
</body>




</html>
