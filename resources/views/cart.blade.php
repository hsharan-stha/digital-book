<x-entry-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Checkout</h1>

        <!-- Cart Items -->

        @if (count($cartList) > 0)
            <div class="bg-white rounded-xl shadow p-4 space-y-4">
                <!-- Example Cart Item -->
                @forelse ($cartList as $book)
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center space-x-4">
                            <img loading="lazy" src="{{ asset($book->book->images) }}" alt="Book"
                                class="w-16 h-20 object-cover rounded" />
                            <div>
                                <h2 class="text-lg font-semibold">{{ $book->book->name }}</h2>
                                <p class="text-sm text-gray-500">Quantity: <span class="font-medium">1</span></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-gray-800">$20.00</p>
                        </div>
                    </div>
                @empty
                    no any selected books to proceed
                @endforelse
                <!-- Repeat the above block for more books -->

                <!-- Total -->
                <div class="flex justify-between items-center pt-4 border-t font-semibold text-lg">
                    <span>Total</span>
                    <span>$40.00</span>
                </div>

                <!-- Checkout Button -->
                <div class="text-right pt-4">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Proceed to Buy
                    </button>
                </div>
            </div>
        @else
            no selected books to proceed
        @endif

    </div>

    <script>
        cartCountdisplay("{{ $cartCount }}")

        function cartCountdisplay(cartCount) {
            cartCountDom = document.getElementById("cart-count");
            if (cartCount > 0) {
                cartCountDom.innerText = cartCount;
                cartCountDom.classList.remove("hidden");
            } else {
                cartCountDom.classList.add("hidden");
            }
        }
    </script>
</x-entry-layout>
