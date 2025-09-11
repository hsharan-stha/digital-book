@component('mail::message')
# 専門教育出版デジタルブックの詳細

{{ $purchase->user->name ?? 'Customer' }} 様

専門教育出版デジタルブックの購入有難うございます。

**購入番号:** {{ $purchase->purchase_date }}  
**金額:** ¥{{ number_format($purchase->total_amount) }}  
**購入数:** {{ $purchase->item_count }}

@component('mail::table')
| 書籍名          | 数量     | 単価      |
| --------------- | -------- | --------- |
@foreach ($purchase->details as $detail)
| {{ $detail->book->name ?? 'Unknown' }} | {{ $detail->quantity }} | ¥{{ number_format($detail->price) }} |
@endforeach
@endcomponent

よろしくお願いいたします,  
{{ config('app.name') }}
@endcomponent
