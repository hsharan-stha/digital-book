@component('mail::message')
# 専門教育出版デジタルブック、お支払いキャンセル

{{ $purchase->user->name ?? 'Customer' }} 様

誠に申し訳ありませんが **#{{ $purchase->purchase_date }}** のお支払いはキャンセルされました。

**金額:** ¥{{ number_format($purchase->total_amount) }}

@if($purchase->details)
@component('mail::table')
| 書籍名          | 数量     | 単価      |
| --------------- | -------- | --------- |
@foreach ($purchase->details as $detail)
| {{ $detail->book->name ?? 'Unknown' }} | {{ $detail->quantity }} | ¥{{ number_format($detail->price) }} |
@endforeach
@endcomponent
@endif

これが間違いであった場合、またはサポートが必要な場合は、サポートチームにお問い合わせください。

Thanks,  
{{ config('app.name') }}
@endcomponent
