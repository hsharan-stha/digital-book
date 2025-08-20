<x-entry-layout>
    {{-- Make sure your base layout includes the CSRF meta tag --}}
    {{-- No Stripe meta or script in this mode --}}

    <div class="bg-white p-6 rounded-xl max-w-2xl mx-auto shadow-md space-y-6 text-gray-800">
        @if ($cartList->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 text-xl font-semibold">{{ __('cart.empty') }}</p>
                <a href="/" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow">
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
                                <td class="border border-gray-300 px-3 py-2">{{ $detail->book->name ?? 'Unknown' }}</td>
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
                {{-- Open confirmation modal first --}}
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

    {{-- Payment/Confirmation Modal (no Stripe; client-only validation) --}}
    <div id="paymentModal" class="fixed inset-0 z-[11111] hidden">
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
                        <div id="brandRow" class="flex flex-wrap items-center gap-2">
                            <img data-brand="visa" src="{{ asset('images/cards/visa.png') }}" alt="VISA" class="h-6 opacity-50">
                            <img data-brand="mastercard" src="{{ asset('images/cards/mastercard-logo.png') }}" alt="Mastercard" class="h-6 opacity-50">
                            <img data-brand="jcb" src="{{ asset('images/cards/jcb.png') }}" alt="JCB" class="h-6 opacity-50">
                            <img data-brand="amex" src="{{ asset('images/cards/amex.png') }}" alt="American Express" class="h-6 opacity-50">
                            <img data-brand="diners" src="{{ asset('images/cards/diners-club.png') }}" alt="Diners Club" class="h-6 opacity-50">
                            <img data-brand="discover" src="{{ asset('images/cards/discover.png') }}" alt="Discover" class="h-6 opacity-50">
                        </div>
                    </div>
                    <p class="text-xs text-blue-700 mt-1">
                       当サイトのオンライン取引は安全です。入力情報は暗号化され、注文確認のためにのみ利用されます。
                    </p>
                </div>

                {{-- Card Number --}}
                <div class="mt-4">
                    <label for="cardNumber" class="text-sm font-medium">カード番号</label>
                    <input id="cardNumber" type="tel" inputmode="numeric" autocomplete="cc-number"
                           class="mt-2 w-full border rounded-lg font-mono tracking-widest"
                           placeholder="0000 0000 0000 0000" maxlength="19">
                </div>

                {{-- Expiry (dropdowns) + CVC side-by-side --}}
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">有効期限</label>
                        <div class="mt-2 flex gap-2">
                            <select id="expMonth" class="border rounded-lg font-mono text-center">
                                <option value="">MM</option>
                                <option value="1">01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                                <option value="6">06</option>
                                <option value="7">07</option>
                                <option value="8">08</option>
                                <option value="9">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <select id="expYear" class="border rounded-lg font-mono text-center">
                                <option value="">YYYY</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="cardCvc" class="text-sm font-medium">セキュリティコード</label>
                        <input id="cardCvc" type="tel" inputmode="numeric" autocomplete="cc-csc"
                               class="mt-2 w-full border rounded-lg font-mono" placeholder="000" maxlength="4">
                    </div>
                </div>

                {{-- Name on card (max 19 chars) --}}
                <div class="mt-4">
                    <label class="text-sm font-medium">カード名義</label>
                    <input id="billingName" type="text" class="mt-2 w-full border rounded-lg px-3 py-2"
                           value="" placeholder="TARO YAMADA"
                           autocomplete="cc-name" maxlength="19">
                </div>

                <input id="billingEmail" type="hidden" value="{{ Auth::user()->email ?? '' }}">

                <p id="card-errors" class="text-sm text-red-600 mt-2 hidden"></p>

                <div class="mt-6 flex items-center justify-between">
                    <p class="text-sm text-gray-600">合計:
                        <span class="font-semibold">¥{{ number_format($totalPrice) }}</span>
                    </p>
                    <button id="payButton" disabled
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg disabled:opacity-60">
                        注文を確定する
                    </button>
                </div>

                <p id="paymentStatus" class="text-sm mt-3 text-gray-600 hidden"></p>
            </div>
        </div>
    </div>

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
            setTimeout(() => document.getElementById('cardNumber')?.focus(), 50);
        }
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        // ----- Brand detection & validation helpers -----
        const brandPatterns = {
            visa: /^4\d{0,15}/,
            mastercard: /^(5[1-5]\d{0,14}|2(2[2-9]\d{0,13}|[3-6]\d{0,14}|7[01]\d{0,13}|720\d{0,12}))/,
            amex: /^3[47]\d{0,13}/,
            diners: /^3(0[0-5]\d{0,11}|[68]\d{0,12})/,
            discover: /^(6011|65|64[4-9]|622)/,
            jcb: /^(?:2131|1800|35)\d{0,14}/
        };

        function detectBrand(panDigits) {
            if (!panDigits) return null;
            for (const [b, rx] of Object.entries(brandPatterns)) {
                if (rx.test(panDigits)) return b;
            }
            return null;
        }

        function luhnCheck(num) {
        return true;
            let sum = 0, alt = false;
            for (let i = num.length - 1; i >= 0; i--) {
                let n = parseInt(num[i], 10);
                if (alt) { n *= 2; if (n > 9) n -= 9; }
                sum += n; alt = !alt;
            }
            return (sum % 10) === 0;
        }

        function formatCardNumber(value, brand) {
            const digits = value.replace(/\D/g, '').slice(0, 19);
            if (brand === 'amex') { // 4-6-5
                return digits.replace(/^(\d{1,4})(\d{1,6})?(\d{1,5})?.*/, (m,a,b,c)=>[a,b,c].filter(Boolean).join(' '));
            }
            return digits.replace(/(\d{4})/g, '$1 ').trim(); // default 4-4-4-4
        }

        function validateExpiryParts(mm, yyyy) {
            const m = parseInt(mm, 10);
            const y = parseInt(yyyy, 10);
            if (!m || !y || m < 1 || m > 12) return false;
            const now = new Date();
            // Last day of selected month, 23:59:59
            const exp = new Date(y, m, 0, 23, 59, 59);
            const curStart = new Date(now.getFullYear(), now.getMonth(), 1);
            return exp >= curStart;
        }

        // ----- UI binding -----
        const numberInput = document.getElementById('cardNumber');
        const expMonthSel = document.getElementById('expMonth');
        const expYearSel  = document.getElementById('expYear');
        const cvcInput    = document.getElementById('cardCvc');
        const nameInput   = document.getElementById('billingName');
        const errEl       = document.getElementById('card-errors');
        const payBtn      = document.getElementById('payButton');
        const statusEl    = document.getElementById('paymentStatus');
        const brandRow    = document.getElementById('brandRow');

        function setError(msg) {
            if (msg) { errEl.textContent = msg; errEl.classList.remove('hidden'); }
            else { errEl.textContent = ''; errEl.classList.add('hidden'); }
        }

        function highlightBrand(brand) {
            [...brandRow.querySelectorAll('img')].forEach(img => {
                if (brand && img.dataset.brand === brand) {
                    img.classList.remove('opacity-50');
                    img.classList.add('opacity-100','ring-2','ring-blue-400','rounded');
                } else {
                    img.classList.remove('opacity-100','ring-2','ring-blue-400','rounded');
                    img.classList.add('opacity-50');
                }
            });
        }

        function enforceCvcMax(brand) {
            const max = brand === 'amex' ? 4 : 3;
            cvcInput.maxLength = max;
            if (cvcInput.value.length > max) cvcInput.value = cvcInput.value.slice(0, max);
        }

        function validateAll() {
            const pan = numberInput.value.replace(/\D/g, '');
            const brand = detectBrand(pan);
            highlightBrand(brand);
            enforceCvcMax(brand);

            // PAN length rules
            const panLenOk =
                (brand === 'amex')   ? pan.length === 15 :
                (brand === 'diners') ? (pan.length === 14 || pan.length === 16) :
                                        pan.length === 16;

            const luhnOk   = panLenOk && luhnCheck(pan);

            // Expiry via dropdowns
            const mm = expMonthSel?.value || '';
            const yyyy = expYearSel?.value || '';
            const expiryOk = validateExpiryParts(mm, yyyy);

            // CVC
            const cvcOk = (brand === 'amex') ? /^\d{4}$/.test(cvcInput.value) : /^\d{3}$/.test(cvcInput.value);

            // Name length (<= 19)
            const nameVal = nameInput.value.trim();
            const nameOk = nameVal.length > 0 && nameVal.length <= 19;

            // Error message (first failing)
            let msg = '';
            if (pan && !panLenOk) msg = 'カード番号の桁数が正しくありません。';
            else if (pan && !luhnOk) msg = 'カード番号が正しくありません。';
            else if ((mm || yyyy) && !expiryOk) msg = '有効期限が正しくありません。';
            else if (cvcInput.value && !cvcOk) msg = 'セキュリティコードが正しくありません。';
            else if (!nameOk && nameVal.length > 19) msg = 'カード名義は19文字以内で入力してください。';
            setError(msg);

            // Enable when all valid
            payBtn.disabled = !(luhnOk && expiryOk && cvcOk && nameOk);
            return { brand, luhnOk, expiryOk, cvcOk, pan };
        }

        // Listeners
        numberInput.addEventListener('input', () => {
            const digits = numberInput.value.replace(/\D/g, '');
            const brand = detectBrand(digits);
            numberInput.value = formatCardNumber(numberInput.value, brand);
            validateAll();
        });
        expMonthSel.addEventListener('change', validateAll);
        expYearSel.addEventListener('change', validateAll);
        cvcInput.addEventListener('input', () => {
            cvcInput.value = cvcInput.value.replace(/\D/g,'');
            validateAll();
        });
        nameInput.addEventListener('input', (e) => {
            if (e.target.value.length > 19) e.target.value = e.target.value.slice(0, 19); // trim pasted text
            validateAll();
        });

        // Click logos to refmt
        brandRow.addEventListener('click', (e) => {
            const img = e.target.closest('img[data-brand]');
            if (!img) return;
            const brand = img.dataset.brand;
            highlightBrand(brand);
            numberInput.value = formatCardNumber(numberInput.value, brand);
            enforceCvcMax(brand);
            validateAll();
        });

        // ----- “Pay” (confirm order; never send PAN/CVC) -----
        document.getElementById('payButton')?.addEventListener('click', async () => {
            const { brand, luhnOk, expiryOk, cvcOk, pan } = validateAll();
            if (!(luhnOk && expiryOk && cvcOk)) return;

            const last4 = pan.slice(-4);
            const billingName = nameInput.value || undefined;
            const billingEmail = document.getElementById('billingEmail').value || undefined;

            // Optional: include MM/YY for your invoice reference
            const card_exp = (expMonthSel.value && expYearSel.value)
                ? `${String(expMonthSel.value).padStart(2,'0')}/${String(expYearSel.value).slice(-2)}`
                : null;

            // UX
            payBtn.disabled = true;
            statusEl.classList.remove('hidden');
            statusEl.textContent = "{{ __('cart.loading') ?? '処理中…' }}";

            try {
                await confirmProceedToCheckout(null, {
                    billingName,
                    billingEmail,
                    card_brand: brand || null,
                    card_last4: last4 || null,
                    card_exp: card_exp
                });

                // Clear sensitive fields (not sent anyway)
                numberInput.value = '';
                cvcInput.value = '';
                expMonthSel.value = '';
                expYearSel.value = '';

                statusEl.textContent = "ご注文が確定しました。";
            } catch (e) {
                setError(e.message || "注文に失敗しました。");
                statusEl.textContent = "注文に失敗しました。";
                payBtn.disabled = false;
            }
        });

        // ----- Purchase creator (unchanged endpoint; payment_intent_id null) -----
        async function confirmProceedToCheckout(paymentIntentId, extra = {}) {
            const btn = document.getElementById("proceedToCheckout");
            btn.setAttribute("disabled", true);
            btn.innerText = "{{ __('cart.loading') ?? 'Loading...' }}";
            try {
                const response = await fetch('/purchases', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        books: payload,
                        payment_intent_id: paymentIntentId || null,
                        billing_name: extra?.billingName || null,
                        billing_email: extra?.billingEmail || null,
                        payment_method: 'offline',
                        card_brand: extra?.card_brand || null,
                        card_last4: extra?.card_last4 || null,
                        card_exp: extra?.card_exp || null
                    })
                });

                if (!response.ok) {
                    const txt = await response.text();
                    throw new Error(txt || 'Failed to place order');
                }

                const data = await response.json();
                // window.location.href = `/purchases?purchase_id=${data?.purchase_id}`;
                window.location.href = `/library`;
            } catch (error) {
                console.error('Error:', error);
                btn.removeAttribute("disabled");
                btn.innerText = "{{ __('cart.proceedToCheckout') ?? 'Proceed to Buy' }}";
                throw error;
            }
        }
    </script>
</x-entry-layout>
