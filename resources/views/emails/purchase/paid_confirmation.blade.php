@component('mail::message')
# Payment Confirmation

Hello {{ $purchase->user->name ?? 'Customer' }},

We have received your payment for Purchase Number: **{{ $purchase->purchase_date }}**.

Thank you for your purchase! You can now access your books.

**Total Amount Paid:** ¥{{ number_format($purchase->total_amount) }}

Thanks for shopping with us!

Regards,  
{{ config('app.name') }}
@endcomponent
