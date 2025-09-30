@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner.css') }}">
@endsection

@section('content')
<div class="container owner-page">
    <h1>店舗代表者画面</h1>

    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    {{-- 店舗一覧 --}}
    <h2>あなたの店舗一覧</h2>
    @if($shops->isEmpty())
        <p>まだ店舗が登録されていません。</p>
    @else
        <div class="shop-list">
            @foreach($shops as $shop)
                <div class="shop-card">
                    @if($shop->image_path)
                        <img src="{{ asset('storage/' . $shop->image_path) }}" alt="店舗画像">
                    @endif
                    <h3>{{ $shop->name }}</h3>
                    <p>#{{ $shop->area->name ?? '未設定' }} #{{ $shop->genre->name ?? '未設定' }}</p>
                    <a href="{{ route('owner.shop.edit', $shop) }}">編集する</a>
                    <br>
                    <a href="{{ route('owner.shop.reservations', $shop) }}">
                        本日の予約（{{ $shop->reservations()->whereDate('date', today())->count() }}件）
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 新規登録ボタン --}}
    <div style="margin-top:20px;">
        <a href="{{ route('owner.shop.create') }}" class="btn btn-primary">＋ 新しい店舗を登録</a>
    </div>
</div>
@endsection