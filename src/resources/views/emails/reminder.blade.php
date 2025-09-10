@component('mail::message')
# ご予約リマインダー

{{ $reservation->user->name }} 様

本日のご予約内容をお知らせいたします。

- 店舗: {{ $reservation->shop->name }}
- 日付: {{ $reservation->date }}
- 時間: {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}
- 人数: {{ $reservation->number }}人

ご来店をお待ちしております。

@endcomponent