<x-entry-layout>
    <div class="bg-white p-6 rounded-xl max-w-2xl mx-auto shadow-md space-y-6 text-gray-800">

        <div class="text-center">
            <p class="text-green-600 font-semibold text-xl">{{__("purchase.purchaseConfirmation")}}</p>
            <p class="mt-2 text-green-700 font-medium">
               {{__("purchase.summary")}}
            </p>
        </div>

        <div>
            <p>{{__("purchase.info")}}</p>

            <p class="mt-4 font-semibold">
                {{__("purchase.purchaseNumber")}}
                <span class="text-blue-600">{{ $purchase->purchase_date }}</span>
            </p>

            <p class="mt-1 font-semibold">
                {{__("purchase.totalAmount")}} ¥{{ number_format($purchase->total_amount) }} 
            </p>
<!--
            <p class="mt-4 font-semibold text-lg">Purchase Details:</p>
            <table class="w-full border-collapse border border-gray-300 text-left">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-3 py-2 bg-gray-100">Book Name</th>
                        <th class="border border-gray-300 px-3 py-2 bg-gray-100">Quantity</th>
                        <th class="border border-gray-300 px-3 py-2 bg-gray-100">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->details as $detail)
                        <tr>
                            <td class="border border-gray-300 px-3 py-2">{{ $detail->book->name ?? 'Unknown' }}</td>
                            <td class="border border-gray-300 px-3 py-2">{{ $detail->quantity }}</td>
                            <td class="border border-gray-300 px-3 py-2">¥{{ number_format($detail->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            -->
        </div>

        <div class="mt-6">
            <p class="font-semibold mb-2">{{__("purchase.purchaseDetails")}}</p>
            <table class="w-full border-collapse border border-gray-300">
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-bold">{{__("purchase.bankName")}}</td>
                        <td class="border border-gray-300 px-3 py-2 text-blue-600">CHIBA SINKIN</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-bold">{{__("purchase.accountNumber")}}</td>
                        <td class="border border-gray-300 px-3 py-2 text-blue-600">XXXXXXX7897</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-bold">{{__("purchase.accountName")}}</td>
                        <td class="border border-gray-300 px-3 py-2 text-blue-600">Senmonkyoiku Supan</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <p>{{__("purchase.finalize")}}</p>
        </div>

        <div class="text-center mt-6">
            <a href="/" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow inline-block">
               {{__("purchase.finish")}}
            </a>
        </div>

    </div>
</x-entry-layout>
