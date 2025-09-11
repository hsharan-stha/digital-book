<x-entry-layout>
    {{-- Make sure your base layout includes the CSRF meta tag --}}
    <meta name="stripe-key" content="{{ config('services.stripe.key') }}">

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
                <p class="text-blue-600 font-semibold text-xl">{{ __('cart.cartOverview') }}</p>
                <p class="mt-2 text-blue-700 font-medium">{{ __('cart.summary') }}</p>
            </div>

            <div>
                <p>{{ __('cart.info') }}</p>

                <p class="mt-4 font-semibold text-lg">{{ __('cart.cartDetails') }}</p>
                <table class="w-full border-collapse border border-gray-300 text-left">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">{{ __('cart.bookName') }}</th>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">{{ __('cart.quantity') }}</th>
                            <th class="border border-gray-300 px-3 py-2 bg-gray-100">{{ __('cart.price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartList as $detail)
                            <tr>
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->book->name ?? 'Unknown' }}
                                </td>
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->quantity }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-right">
                                    ¥{{ number_format($detail->book->price) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="border border-gray-300 px-3 py-2 font-semibold" colspan="2">
                                {{ __('cart.totalEstimated') }}
                            </td>
                            <td class="border border-gray-300 px-3 py-2 font-semibold text-right">
                                ¥{{ number_format($totalPrice) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6">
                <p>{{ __('cart.finalize') }}</p>
            </div>

            <div class="text-center mt-4 flex justify-center gap-4">
                {{-- Open payment modal first --}}
                <button id="proceedToCheckout" onclick="openPaymentModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow inline-block">
                    {{ __('cart.proceedToCheckout') }}
                </button>
                <a href="/"
                    class="bg-gray-400 hover:bg-gray-500 text-white py-2 px-6 rounded shadow inline-block">
                    {{ __('cart.cancel') }}
                </a>
            </div>
        @endif
    </div>

    {{-- Payment Modal --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closePaymentModal()"></div>
        <div class="relative mx-auto mt-12 w-full max-w-xl">
            <div class="bg-white rounded-xl shadow-xl p-6">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-semibold">支払い</h2>
                    <button class="text-gray-400 hover:text-gray-600" onclick="closePaymentModal()">✕</button>
                </div>

                {{-- Brands row --}}
                <div class="mt-3">
                    <div class="flex items-center gap-3">
                       
                        <span class="font-semibold">クレジットカード</span>
                        <div class="flex flex-wrap items-center gap-2">
                            <img src="{{ asset('images/cards/visa.png') }}" alt="VISA" class="h-6">
                            <img src="{{ asset('images/cards/mastercard-logo.png') }}" alt="Mastercard" class="h-6">
                            <img src="{{ asset('images/cards/jcb.png') }}" alt="JCB" class="h-6">
                            <img src="{{ asset('images/cards/amex.png') }}" alt="American Express" class="h-6">
                            <img src="{{ asset('images/cards/diners-club.png') }}" alt="Diners Club" class="h-6">
                            <img src="{{ asset('images/cards/discover.png') }}" alt="Discover" class="h-6">
                        </div>

                    </div>
                    <p class="text-xs text-blue-700 mt-1"> セキュアなクレジットカード決済です。</p>
                </div>

                {{-- Card Number --}}
                <div class="mt-4">
                    <label class="text-sm font-medium">カード番号</label>
                    <div id="card-number" class="mt-2 border rounded-lg px-3 py-3 bg-white"></div>
                </div>

                {{-- Expiry + CVC side-by-side --}}
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">有効期限</label>
                        <div id="card-expiry" class="mt-2 border rounded-lg px-3 py-3 bg-white"></div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">セキュリティコード</label>
                        <div id="card-cvc" class="mt-2 border rounded-lg px-3 py-3 bg-white"></div>
                    </div>
                </div>

                {{-- Name on card (prefilled) --}}
                <div class="mt-4">
                    <label class="text-sm font-medium">カード名義</label>
                    <input id="billingName" type="text" class="mt-2 w-full border rounded-lg px-3 py-2"
                        value="{{ Auth::user()->name ?? '' }}" placeholder="TARO YAMADA">
                </div>
                {{-- Optional: email hidden but sent with billing_details --}}
                <input id="billingEmail" type="hidden" value="{{ Auth::user()->email ?? '' }}">

                <p id="card-errors" class="text-sm text-red-600 mt-2 hidden"></p>

                <div class="mt-6 flex items-center justify-between">
                    <p class="text-sm text-gray-600">合計:
                        <span class="font-semibold">¥{{ number_format($totalPrice) }}</span>
                    </p>
                    <button id="payButton"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg disabled:opacity-60">
                        今すぐ支払う ¥{{ number_format($totalPrice) }}
                    </button>
                </div>

                <p id="paymentStatus" class="text-sm mt-3 text-gray-600 hidden"></p>
            </div>
        </div>
    </div>

    {{-- Stripe.js --}}
    <script src="https://js.stripe.com/v3"></script>

    <script>
        // ----- Cart payload (unchanged) -----
        localStorage.removeItem("cart_items");
        const payload = @json($cartList).map(i => ({
            book_id: i.book_id,
            quantity: i.quantity,
            price: parseFloat(i.book.price),
            per_price: parseFloat(i.book.price * i.quantity)
        }));

        cartCountdisplay("{{ isset($cartCount) ? $cartCount : 0 }}");
        loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }});

        // ----- Modal controls -----
        function openPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            initStripeIfNeeded();
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        // ----- Stripe Elements (separate fields) -----
        let stripe, elements, numberEl, expiryEl, cvcEl, stripeReady = false;

        function initStripeIfNeeded() {
            if (stripeReady) return;

            const key = document.querySelector('meta[name="stripe-key"]').getAttribute('content');
            stripe = Stripe(key);
            elements = stripe.elements({
                locale: 'ja'
            });

            numberEl = elements.create('cardNumber');
            expiryEl = elements.create('cardExpiry');
            cvcEl = elements.create('cardCvc');

            numberEl.mount('#card-number');
            expiryEl.mount('#card-expiry');
            cvcEl.mount('#card-cvc');

            const errEl = document.getElementById('card-errors');
            const payBtn = document.getElementById('payButton');
            payBtn.disabled = true;

            // Track completeness explicitly (don’t use _complete)
            let numberComplete = false,
                expiryComplete = false,
                cvcComplete = false;

            function onChange(e) {
                if (e.error) {
                    errEl.textContent = e.error.message;
                    errEl.classList.remove('hidden');
                } else {
                    errEl.textContent = '';
                    errEl.classList.add('hidden');
                }

                if (e.elementType === 'cardNumber') numberComplete = e.complete;
                if (e.elementType === 'cardExpiry') expiryComplete = e.complete;
                if (e.elementType === 'cardCvc') cvcComplete = e.complete;

                payBtn.disabled = !(numberComplete && expiryComplete && cvcComplete);
            }

            numberEl.on('change', onChange);
            expiryEl.on('change', onChange);
            cvcEl.on('change', onChange);

            stripeReady = true;
        }

        // ----- Pay button handler -----
        document.getElementById('payButton')?.addEventListener('click', async () => {
            const payBtn = document.getElementById('payButton');
            const status = document.getElementById('paymentStatus');
            const errEl = document.getElementById('card-errors');

            payBtn.disabled = true;
            status.classList.remove('hidden');
            status.textContent = "{{ __('cart.loading') ?? '処理中…' }}";

            try {
                // (1) Create PaymentIntent
                const res = await fetch('/payments/create-intent', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        amount: {{ (int) $totalPrice }}, // JPY: zero-decimal
                        currency: 'jpy'
                    })
                });
                if (!res.ok) throw new Error('Failed to create PaymentIntent');
                const {
                    clientSecret
                } = await res.json();

                // (2) Confirm card payment
                const billingName = document.getElementById('billingName').value || undefined;
                const billingEmail = document.getElementById('billingEmail').value || undefined;

                const {
                    paymentIntent,
                    error
                } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: numberEl, // expiry/cvc linked automatically
                        billing_details: {
                            name: billingName,
                            email: billingEmail
                        }
                    }
                });

                if (error) {
                    console.log('Stripe error:', error.code, error.decline_code, error.message, error
                        .payment_intent?.id);
                    throw new Error(error.message || 'Payment failed');
                }

                if (paymentIntent?.status === 'succeeded') {
                    status.textContent = "{{  '支払いが完了しました。' }}";
                    await confirmProceedToCheckout(paymentIntent.id);
                } else {
                    throw new Error('Payment not completed');
                }
            } catch (e) {
                errEl.textContent = e.message || "{{ '支払いに失敗しました。' }}";
                errEl.classList.remove('hidden');
                status.textContent = "{{ '支払いに失敗しました。' }}";
                document.getElementById('payButton').disabled = false;
            }
        });

        // ----- Your existing purchase creator, now with optional paymentIntentId -----
        async function confirmProceedToCheckout(paymentIntentId) {
            const btn = document.getElementById("proceedToCheckout");
            btn.setAttribute("disabled", true);
            btn.innerText = "{{ __('cart.loading') ?? 'Loading...' }}";
            try {
                const response = await fetch('/purchases', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        books: payload,
                        payment_intent_id: paymentIntentId || null
                    })
                });
                const data = await response.json();
                //window.location.href = `/purchases?purchase_id=${data?.purchase_id}`;
                window.location.href = `/library`;

            } catch (error) {
                console.error('Error:', error);
                btn.removeAttribute("disabled");
                btn.innerText = "{{ __('cart.proceedToCheckout') ?? 'Proceed to Buy' }}";
            }
        }
    </script>
</x-entry-layout>
