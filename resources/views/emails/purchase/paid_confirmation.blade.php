@component('mail::message')
# 専門教育出版デジタルブック、お振込みを確認しました

{{ $purchase->user->name ?? 'Customer' }} 様

専門教育出版デジタルブック、お振込みを確認しました

あなたの購入番号は: **{{ $purchase->purchase_date }}**.

お振込み有難うございます。デジタルブックが利用可能になりました。

**お振込み金額:** ¥{{ number_format($purchase->total_amount) }}

当社デジタルブックをご利用いただき、有難うございました。

よろしくお願いいたします,
{{ config('app.name') }}
@endcomponent
