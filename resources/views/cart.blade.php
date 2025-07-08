<x-entry-layout>
    <div class="bg-white p-6 rounded-xl max-w-2xl mx-auto shadow-md space-y-6 text-gray-800">

        @if ($cartList->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 text-xl font-semibold">Your cart is currently empty.</p>
                <a href="/"
                    class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow">
                    Browse Books
                </a>
            </div>
        @else
            <div class="text-center">
                <p class="text-blue-600 font-semibold text-xl">Cart Overview</p>
                <p class="mt-2 text-blue-700 font-medium">
                    This is a summary of the items currently in your cart.
                </p>
            </div>

            <div>
                <p>You have added the following books to your cart. Please review the details before proceeding to
                    checkout.</p>

                <p class="mt-1 font-semibold">
                    Total Estimated Amount: ¥{{ number_format($totalPrice) }} JPY
                </p>

                <p class="mt-4 font-semibold text-lg">Cart Details:</p>
                <table class="w-full border-collapse border border-gray-300 text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">Book Name</th>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">Quantity</th>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartList as $detail)
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->book->name ?? 'Unknown' }}</td>
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->quantity }}</td>
                                <td class="border border-gray-300 px-3 py-2">¥{{ number_format($detail->book->price) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <p>To finalize your purchase, please proceed to the checkout process.</p>
            </div>

            <div class="text-center mt-4 flex justify-center gap-4">
                <a id="proceedToCheckout" href="#" onclick="confirmProceedToCheckout()"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow inline-block">
                    Proceed to Checkout
                </a>
                <a href="/"
                    class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-6 rounded shadow inline-block">
                    Cancel
                </a>
            </div>

        @endif

    </div>

    <script>
        localStorage.removeItem("cart_items");
        const payload = @json($cartList).map(i => {
            return {
                book_id: i.book_id,
                quantity: i.quantity,
                price: parseFloat(i.book.price),
                per_price: parseFloat(i.book.price * i.quantity)
            }
        });
        console.log(payload)

        function confirmProceedToCheckout() {
            document.getElementById("proceedToCheckout").setAttribute("disabled", true);
            document.getElementById("proceedToCheckout").innerText = "Loading..."
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
                    document.getElementById("proceedToCheckout").removeAttribute("disabled");
                    document.getElementById("proceedToCheckout").innerText = "Proceed to Buy"
                });
        }
    </script>
    <script>
        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}")
        loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }})
    </script>
</x-entry-layout>
