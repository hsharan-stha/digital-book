<x-entry-layout>
<div class="bg-white p-6 rounded-xl max-w-2xl mx-auto shadow-md space-y-4">
  <div class="text-center">
    <p class="text-green-600 font-semibold text-lg">Purchase Success.</p>
  </div>

  <!-- English Section -->
  <div class="block text-gray-700 leading-relaxed">
    Thank you for purchasing books. Your purchase request has been accepted.
    <p class="font-bold mt-2">
      Your Purchase Number is <span class="text-blue-600">{{$purchase->id}}</span>
    </p>
    The total amount is {{$purchase->total_amount}} JPY.
  </div>

  <div class="block text-gray-700 leading-relaxed">
    Please confirm payment from following bank details:
    
    <p class="font-bold mt-2">
      Bank Name <span class="text-blue-600">CHIBA SINKIN</span>
    </p>
     <p class="font-bold mt-2">
      Account Number <span class="text-blue-600">XXXXXXX7897</span>
    </p>
     <p class="font-bold mt-2">
      Account Name <span class="text-blue-600">Senmonkyoiku Supan</span>
    </p>
  </div>

  <div class="block text-gray-700 leading-relaxed">
    Please note that if the payment is not completed within 48 hours, your application for the book purchase will be automatically canceled.
  </div>

  <div class="block text-gray-700 leading-relaxed">
    We look forward to hearing from you soon. Thank you.
  </div>

  <!-- Finish Button -->
  <div class="text-center">
    <a href="/" type="button" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded shadow">
      おわり Finish
    </a>
  </div>
</div>

</x-entry-layout>