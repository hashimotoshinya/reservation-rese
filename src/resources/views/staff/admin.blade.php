@extends('layouts.app2')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="admin-container">
    <h1 class="admin-title">管理者画面</h1>

    {{-- フラッシュメッセージ --}}
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="flash-message" style="background:#e74c3c;">
            {{ session('error') }}
        </div>
    @endif

    <div class="admin-content">
        <!-- 左側：店舗代表者作成 -->
        <div class="admin-section">
            <h2 class="section-title">店舗代表者作成</h2>
            <form method="POST" action="{{ route('admin.owners.store') }}">
                @csrf
                <div>
                    <label>名前</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>メール</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>パスワード</label>
                    <input type="password" name="password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>パスワード確認</label>
                    <input type="password" name="password_confirmation">
                </div>
                <button type="submit">作成</button>
            </form>
        </div>

        <!-- 右側：お知らせメール送信 -->
        <div class="admin-section">
            <h2 class="section-title">お知らせメール送信</h2>
            <form method="POST" action="{{ route('admin.notifications.send') }}">
                @csrf
                <div>
                    <label>対象</label>
                    <select name="target">
                        <option value="all">全員</option>
                        <option value="frequent_users">累計5回以上利用</option>
                        <option value="owners">全店舗代表者</option>
                    </select>
                </div>
                <div>
                    <label>件名</label>
                    <input type="text" name="subject" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label>本文</label>
                    <textarea name="message" rows="5">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit">送信</button>
            </form>
        </div>
    </div>
</div>
@endsection