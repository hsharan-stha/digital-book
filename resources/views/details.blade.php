<x-entry-layout>

    <script src="{{ asset('js/jquery/jquery.min.1.7.js') }}"></script>

    <script src="{{ asset('js/lib/paltau.min.js') }}"></script>


    <div class="w-full mx-auto flex flex-col md:flex-row gap-8">

        <!-- Product Images + Flipbook -->
        <div class="w-full  md:w-1/2 lg:w-1/3 flex flex-col items-center gap-8">

            <!-- Flipbook -->
            <div class="w-full aspect-[3/4] border shadow" id="flipbook">

                <!-- <div class="page bg-white flex justify-center items-center text-2xl font-bold"><img loading="lazy"
                        src="{{ asset($bookDetails->images) }}" alt="Page {{ 0 }}" class="w-full h-full"></div> -->
                @foreach ($bookDetails->pages as $page)
                    <div class="page bg-white flex justify-center items-center text-2xl font-bold"><img loading="lazy"
                            src="{{ asset($page->page_image) }}" alt="Page {{ $loop->iteration }}"
                            class="w-full h-full {{ $enableScale ? 'scale-[1.11]' : '' }}"></div>
                @endforeach

                <div class="page bg-white h-full w-full flex justify-center items-center text-2xl font-bold relative">
                    <!-- <div
                        class="h-full w-full bg-red-400 text-white flex flex-col items-center justify-center text-center px-6 py-8">
                        <h3 class="text-2xl font-bold mb-2">Unlock Full Access</h3>
                        <p class="text-sm md:text-base">
                            You're viewing a preview. Purchase the full version to read the entire book. Once purchased,
                            the
                            full version will be available in your library for unlimited access.
                        </p>
                    </div> -->
                    <div
                        class="h-full w-full flex flex-col text-gray-500 items-center justify-center text-center px-6 py-8">
                        <h3 class="text-2xl font-bold mb-2">フルアクセスのロックを解除</h3>
                        <p class="text-sm md:text-base">
                            現在プレビュー版をご覧いただいています。書籍全文をお読みいただくには、フルバージョンをご購入ください。ご購入後、フルバージョンはライブラリで無制限にご利用いただけます。
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Product Details -->
        <div class="w-full md:w-1/2 lg:w-1/4">
            <div class="flex-grow w-full space-y-4">
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ $bookDetails->name }}
                </h1>

                <!-- Ratings and badges -->
                <div class="flex items-center space-x-2 hidden">
                    <div class="flex items-center text-yellow-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.062 3.275a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.062 3.275c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.062-3.275a1 1 0 00-.364-1.118L2.447 8.702c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.062-3.275z" />
                        </svg>
                        <span class="ml-1 text-gray-700">4.1</span>
                    </div>
                    <!-- <span class="text-sm text-gray-500">(12,826 ratings)</span> -->
                    <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded">#1
                        {{ __('details.bestSeller') }}</span>
                </div>

                <!-- Sold info -->
                <!--  <p class="text-sm text-gray-500">20K+ bought in the past month</p> -->

                <!-- Price -->
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-bold text-gray-900">¥{{ number_format($bookDetails->price) }}</span>
                    <span class="text-sm text-gray-500">({{ __('details.taxIncluded') }})</span>
                </div>


                <!-- Delivery info -->
                <div class="mt-3">
              <a href="{{ route('detail.readSample', $bookDetails->id) }}"
                target="_blank"
                class="px-4 py-2 text-sm font-bold text-white bg-green-600 rounded hover:bg-green-700 transition">
                {{ __('details.readSample') }}
                </a>

                </div>
            </div>

            <!-- Buy Box -->
            <div class="w-full border rounded p-4 space-y-4 shadow mt-4">

                <button type="button" onclick="addToCart(this,{{ $bookDetails }}, 1)"
                    class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 rounded transition">
                    <!--  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-5 h-5 cart-icon">
                                                        <path
                                                            d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                                                    </svg>-->
                    <span class="button-text">{{ __('details.addToCart') }}</span>
                    <span class="loading hidden">{{ __('details.loading') }}</span>
                </button>
                <button onclick="buyNow({{ $bookDetails }})" id="buyNow"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded transition">{{ __('details.buyNow') }}</button>
                <div class="text-xs text-gray-500">{{ __('details.shipInfo') }}</div>
            </div>
        </div>
    </div>

    <style>
        #flipbook .page {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#flipbook').paltau({
                width: "100%",
                height: "100%",
                autoCenter: true,
                acceleration: false,
                elevation: 100,
                duration: 800,
                display: 'single',
                gradients: true
            });
            const isLoggedIn = @json(Auth::check());
            if (isLoggedIn) {
                renderCartFromApi()
            } else {
                renderGuestCart();
            }

            // Orientation change handler
            window.addEventListener("orientationchange", () => {
                window.location.reload()
            });
        });
    </script>

    <script>
        function buyNow(details) {
            const isLoggedIn = @json(Auth::check());
            if (isLoggedIn) {
                document.getElementById("buyNow").setAttribute("disabled", true);
                document.getElementById("buyNow").innerText = "{{ __('home.loading') }}"
                fetch('/cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')


                        },
                        body: JSON.stringify({
                            book_id: details.id,
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        window.location.href = `/cart-web`;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById("buyNow").removeAttribute("disabled");
                        document.getElementById("buyNow").innerText = "Proceed to Buy"
                    });
            } else {
                item = {
                    ...details,
                    qty: 1
                }
                let cart = JSON.parse(localStorage.getItem("cart_items")) || [];

                const existing = cart.find(i => i.id === item.id);
                if (!existing) {
                    cart.push(item);
                }

                localStorage.setItem("cart_items", JSON.stringify(cart));
                window.location.href = "/login-register"
            }
        }
    </script>



</x-entry-layout>
