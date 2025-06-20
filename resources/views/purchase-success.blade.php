<x-entry-layout>
    <div class="bg-white p-6 rounded-xl max-w-2xl mx-auto shadow-md space-y-6 text-gray-800">

        <div class="text-center">
            <p class="text-green-600 font-semibold text-xl">Purchase Confirmation</p>
            <p class="mt-2 text-green-700 font-medium">
                A confirmation email has been sent to your registered email address.
            </p>
        </div>

        <div>
            <p>Thank you for purchasing books. Your purchase request has been accepted.</p>

            <p class="mt-4 font-semibold">
                Your Purchase Number is
                <span class="text-blue-600">{{ $purchase->purchase_date }}</span>
            </p>

            <p class="mt-1 font-semibold">
                Total Amount: ¥{{ number_format($purchase->total_amount) }} JPY
            </p>

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
        </div>

        <div class="mt-6">
            <p class="font-semibold mb-2">Please confirm payment from the following bank details:</p>
            <table class="w-full border-collapse border border-gray-300">
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-bold">Bank Name</td>
                        <td class="border border-gray-300 px-3 py-2 text-blue-600">CHIBA SINKIN</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-bold">Account Number</td>
                        <td class="border border-gray-300 px-3 py-2 text-blue-600">XXXXXXX7897</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-3 py-2 font-bold">Account Name</td>
                        <td class="border border-gray-300 px-3 py-2 text-blue-600">Senmonkyoiku Supan</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <p>We look forward to hearing from you soon. Thank you.</p>
        </div>

        <div class="text-center mt-6">
            <a href="/" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow inline-block">
                おわり Finish
            </a>
        </div>

    </div>
</x-entry-layout>
