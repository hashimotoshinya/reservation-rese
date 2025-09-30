@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation-status.css') }}">
@endsection

@section('content')
<div class="container reservation-status-page">
    <div class="reservation-header">
        <a href="{{ url('/staff/owner') }}" class="back-btn">&lt;</a>
        <h1>{{ $shop->name }} の予約状況</h1>
    </div>
    {{-- 本日の予約 --}}
    <h2>本日の予約</h2>
    <div class="reservation-list">
        @forelse($todayReservations as $reservation)
            <div class="reservation-card">
                <div class="reservation-row">
                    <span>{{ $reservation->date }}</span>
                    <span>{{ $reservation->user->name }}</span>
                    <span>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</span>
                    <span>{{ $reservation->number }}人</span>
                </div>
            </div>
        @empty
            <p>本日の予約はありません。</p>
        @endforelse
    </div>

    {{-- 以降の予約 --}}
    <h2>以降の予約</h2>
    <div class="reservation-list">
        @forelse($futureReservations as $reservation)
            <div class="reservation-card">
                <div class="reservation-row">
                    <span>{{ $reservation->date }}</span>
                    <span>{{ $reservation->user->name }}</span>
                    <span>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</span>
                    <span>{{ $reservation->number }}人</span>
                </div>
            </div>
        @empty
            <p>以降の予約はありません。</p>
        @endforelse
    </div>

    {{-- 以前の予約 --}}
    <h2>以前の予約</h2>
    <div class="reservation-list">
        @forelse($pastReservations as $reservation)
            <div class="reservation-card">
                <div class="reservation-row">
                    <span>{{ $reservation->date }}</span>
                    <span>{{ $reservation->user->name }}</span>
                    <span>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</span>
                    <span>{{ $reservation->number }}人</span>
                </div>
            </div>
        @empty
            <p>以前の予約はありません。</p>
        @endforelse
    </div>
</div>
@endsection