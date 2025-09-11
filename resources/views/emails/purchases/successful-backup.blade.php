@component('mail::message')
# 専門教育出版デジタルブック振込先の確認

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

---

**以下の口座に金額をお振込みください**


@component('mail::table')
| 口座情報          |              |
| --------------- | ----------------------- |
| 銀行名          | 千葉信用金庫            |
| 支店名          | 大佐和支店              |
| 種類            | 普通                    |
| 口座番号        | XXXXXXX7897             |
| 名前            | ｾﾝﾓﾝｷｮｳｲｸｼｭｯﾊﾟﾝ         |
@endcomponent

---

以上。よろしくお願いいたします。

よろしくお願いいたします,  
{{ config('app.name') }}
@endcomponent
