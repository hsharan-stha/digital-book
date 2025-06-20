@component('mail::message')
# Purchase Confirmation

Hello {{ $purchase->user->name ?? 'Customer' }},

Thank you for your purchase. Here are the details:

**Purchase ID:** {{ $purchase->purchase_date }}  
**Total Amount:** ¥{{ number_format($purchase->total_amount) }}  
**Items Count:** {{ $purchase->item_count }}

@component('mail::table')
| Book Name       | Quantity | Price     |
| --------------- | -------- | --------- |
@foreach ($purchase->details as $detail)
| {{ $detail->book->name ?? 'Unknown' }} | {{ $detail->quantity }} | ¥{{ number_format($detail->price) }} |
@endforeach
@endcomponent

---

**Please confirm payment from the following bank details:**

| Detail          | Information             |
| --------------- | ----------------------- |
| Bank Name       | **CHIBA SINKIN**        |
| Account Number  | **XXXXXXX7897**         |
| Account Name    | **Senmonkyoiku Supan**  |

---

We look forward to hearing from you soon. Thank you.

Regards,  
{{ config('app.name') }}
@endcomponent
