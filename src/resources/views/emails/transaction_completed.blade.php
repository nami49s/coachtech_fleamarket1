@component('mail::message')
# 取引が完了しました！

商品[{{ $item->name }}]の取引が完了しました！

{{ $item->buyer->name }}さんの評価をお願いいたします！

よろしくお願いいたします！<br>
coachtech_fleamarket
@endcomponent
