<x-entry-layout>

    <script src="{{ asset('js/extras/jquery.min.1.7.js') }}"></script>

    <script src="{{ asset('js/lib/turn.min.js') }}"></script>


    <div class="w-full mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Product Images + Flipbook -->
        <div class="w-full lg:w-2/3 h-[calc(100vh-140px)]  flex flex-col items-center gap-8">

            <!-- Flipbook -->
            <div class="w-full lg:w-1/3  border shadow" id="flipbook">

                <div class="page bg-white flex justify-center items-center text-2xl font-bold"><img loading="lazy"
                        src="{{ asset($bookDetails->images) }}" alt="Page {{ 0 }}" class="w-full h-full"></div>
                @foreach ($bookDetails->pages as $page)
                    <div class="page bg-white flex justify-center items-center text-2xl font-bold"><img loading="lazy"
                            src="{{ asset($page->page_image) }}" alt="Page {{ $loop->iteration }}"
                            class="w-full h-full"></div>
                @endforeach
            </div>
        </div>

        <!-- Product Details -->
        <div>
            <div class="flex-grow w-full space-y-4">
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ $bookDetails->name }}
                </h1>

                <!-- Ratings and badges -->
                <div class="flex items-center space-x-2">
                    <div class="flex items-center text-yellow-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.062 3.275a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.062 3.275c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.062-3.275a1 1 0 00-.364-1.118L2.447 8.702c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.062-3.275z" />
                        </svg>
                        <span class="ml-1 text-gray-700">4.1</span>
                    </div>
                    <!-- <span class="text-sm text-gray-500">(12,826 ratings)</span> -->
                    <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded">#1 Best Seller</span>
                </div>

                <!-- Sold info -->
                <!--  <p class="text-sm text-gray-500">20K+ bought in the past month</p> -->

                <!-- Price -->
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-bold text-gray-900">¥{{ $bookDetails->price }}</span>
                    <span class="text-sm text-gray-500">(Tax included)</span>
                </div>

                <!-- Bonus badge
                <div class="bg-green-200 text-green-900 text-sm font-semibold px-3 py-1 rounded w-max">
                    Bonus: This week's actually free! That's cool
                </div>
-->
                <!-- Delivery info -->
                <p class="text-sm text-gray-700">
                    <span class="font-bold text-green-600">Delivery</span>: After stuff check
                </p>
            </div>

            <!-- Buy Box -->
            <div class="w-full border rounded p-4 space-y-4 shadow mt-4">
                <!-- <div class="flex justify-between items-baseline">
                    <span class="text-xl font-bold text-gray-900">¥{{ $bookDetails->price }}</span>
                    <span class="text-sm text-gray-500">(Tax included)</span>
                </div>
                  <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700" for="quantity">Quantity:</label>
                    <input id="quantity" type="number" value="1" min="1"
                        class="w-16 border rounded text-center py-1" />
                </div> -->
                <button class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-bold py-3 rounded transition"
                    onclick="addToCart(this,{{ $bookDetails }}, 1)">Add to
                    Cart</button>
               <!--  <button
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded transition">Buy
                    Now</button>-->
                <div class="text-xs text-gray-500">Ships from senmonkyuoiku • Secure transaction</div>
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
            $('#flipbook').turn({
                width: "100%",
                height: "100%",
                autoCenter: true,
                acceleration: true,
                elevation: 50,
                duration: 800,
                display: 'single',
                gradients: true
            });
        });
    </script>

</x-entry-layout>
