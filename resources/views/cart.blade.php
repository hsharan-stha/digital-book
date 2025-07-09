<x-entry-layout>
    <div class="bg-white p-6 rounded-xl max-w-2xl mx-auto shadow-md space-y-6 text-gray-800">

        @if ($cartList->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 text-xl font-semibold">{{ __('cart.empty') }}</p>
                <a href="/"
                    class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow">
                    {{ __('cart.browseBooks') }}
                </a>
            </div>
        @else
            <div class="text-center">
                <p class="text-blue-600 font-semibold text-xl"> {{ __('cart.cartOverview') }}</p>
                <p class="mt-2 text-blue-700 font-medium">
                    {{ __('cart.summary') }}

                </p>
            </div>

            <div>
                <p>{{ __('cart.info') }}</p>

              

                <p class="mt-4 font-semibold text-lg"> {{ __('cart.cartDetails') }}
                </p>
                <table class="w-full border-collapse border border-gray-300 text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">{{ __('cart.bookName') }}</th>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">{{ __('cart.quantity') }}</th>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100 ">{{ __('cart.price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartList as $detail)
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->book->name ?? 'Unknown' }}</td>
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->quantity }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">¥{{ number_format($detail->book->price) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                    <td class="border border-gray-300 px-3 py-2 font-semibold" colspan="2"> {{ __('cart.totalEstimated') }}</td>
                    <td class="border border-gray-300 px-3 py-2 font-semibold text-right">¥{{ number_format($totalPrice) }} </td>
                    </tr>
                    </tfoot>
                </table>
                  
            </div>

            <div class="mt-6">
                <p>{{ __('cart.finalize') }}</p>
            </div>

            <div class="text-center mt-4 flex justify-center gap-4">
                <a id="proceedToCheckout" href="#" onclick="confirmProceedToCheckout()"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow inline-block">
                    {{ __('cart.proceedToCheckout') }}
                </a>
                <a href="/"
                    class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-6 rounded shadow inline-block">
                    {{ __('cart.cancel') }}
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
            document.getElementById("proceedToCheckout").innerText = "{{__("cart.loading")}}"
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
