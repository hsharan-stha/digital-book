<x-entry-layout>
    <div>
        <h1 class="text-2xl font-bold mb-6">Cart</h1>

        <!-- Cart Items -->

        @if (count($cartList) > 0)
            <div class="bg-white rounded-xl shadow p-4 space-y-4">
                <!-- Example Cart Item -->
                @forelse ($cartList as $book)
                    <div class="flex items-center justify-between border-b pb-4 cart-list">
                        <div class="flex items-center space-x-4">
                            <img loading="lazy" src="{{ asset($book->book->images) }}" alt="Book"
                                class="w-16 h-20 object-cover rounded" />
                            <div>
                                <h2 class="text-lg font-semibold"><span
                                        class="bookId hidden">{{ $book->book->id }}</span>{{ $book->book->name }}</h2>
                                <p class="text-sm text-gray-500">Quantity: <input type="number"
                                        value="{{ $book->quantity }}" min="1"
                                        class="w-12 px-1 py-0.5 text-sm border rounded border-gray-300 text-center quantity"
                                        onchange="updateQuantity({{ $book->book->id }},this.value )" />
                                </p>
                            </div>
                        </div>
                        <div class="text-right spacey-1">
                            <p class="text-sm text-gray-500">Price: ¥<span
                                    class="perPrice">{{ $book->book->price }}</span></p>
                            <p class="text-lg font-semibold text-gray-800 "> ¥<span class="price"
                                    data-base-price="{{ $book->book->price }}">{{ $book->book->price }}</span></p>

                            <button class="text-red-500 text-sm hover:underline mt-1"
                                onclick="if(confirm('Are you sure you want to remove this item?')) deleteCart({{ $book->book->id }})">Remove</button>
                        </div>
                    </div>
                @empty
                    no any selected books to proceed
                @endforelse
                <!-- Repeat the above block for more books -->

                <!-- Total -->
                <div class="flex justify-between items-center pt-4 border-t font-semibold text-lg">
                    <span>Total</span>
                    <div>¥<span id="total"></span></div>
                </div>

                <!-- Checkout Button -->
                <div class="text-right pt-4">
                    <button id="proceedToBuy" onclick="proceedToBuy()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                        Proceed to Buy
                    </button>
                </div>
            </div>
        @else
            YOUR CART IS EMPTY
        @endif

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
                <button onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        let payload = []
        loadCart();

        function loadCart() {
            document.addEventListener('DOMContentLoaded', function() {
                const cartItems = document.querySelectorAll('.cart-list');
                const totalDisplay = document.getElementById('total');



                function updatePrices() {
                    let total = 0;
                    payload = []

                    cartItems?.forEach(item => {
                        const quantityInput = item.querySelector('.quantity');
                        const bookId = item.querySelector('.bookId');
                        const priceElement = item.querySelector('.price');
                        const perPriceElement = item.querySelector('.perPrice');

                        const basePriceAttr = priceElement.getAttribute('data-base-price');
                        const basePrice = parseFloat(basePriceAttr);


                        const quantity = parseInt(quantityInput.value) || 0;

                        const rowTotal = basePrice * quantity;
                        if (priceElement) {
                            priceElement.textContent = `${rowTotal.toFixed(2)}`;
                        }
                        if (perPriceElement) {
                            perPriceElement.textContent = `${basePrice.toFixed(2)}`;
                        }
                        total += rowTotal;

                        if (quantityInput.value > 0) {
                            payload.push({
                                book_id: bookId.innerText,
                                quantity: quantityInput.value,
                                price: parseFloat(priceElement.innerText),
                                per_price: parseFloat(perPriceElement.innerText)
                            });
                        }

                    });

                    totalDisplay.textContent = total.toFixed(2);
                }

                cartItems.forEach(item => {
                    const input = item.querySelector('.quantity');
                    input.addEventListener('input', updatePrices);
                });

                // Run once on page load
                updatePrices();



            });
        }

        function updateQuantity(bookId, quantity = 0) {
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
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }


        function proceedToBuy() {
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }


        function confirmProceed() {
            closeModal();
            document.getElementById("proceedToBuy").setAttribute("disabled", true);
            document.getElementById("proceedToBuy").innerText = "Loading..."
            fetch('/purchases', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')


                    },
                    body: JSON.stringify({
                        books: payload
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
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    <script>
        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}")
    </script>
</x-entry-layout>
