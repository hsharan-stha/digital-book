@component('mail::message')
# Payment Cancelled

Hello {{ $purchase->user->name ?? 'Customer' }},

We regret to inform you that your payment for the purchase **#{{ $purchase->purchase_date }}** has been cancelled.

**Total Amount:** ¥{{ number_format($purchase->total_amount) }}

@if($purchase->details)
@component('mail::table')
| Book Name       | Quantity | Price     |
| --------------- | -------- | --------- |
@foreach ($purchase->details as $detail)
| {{ $detail->book->name ?? 'Unknown' }} | {{ $detail->quantity }} | ¥{{ number_format($detail->price) }} |
@endforeach
@endcomponent
@endif

If this was a mistake or you need help, please contact our support team.

Thanks,  
{{ config('app.name') }}
@endcomponent
