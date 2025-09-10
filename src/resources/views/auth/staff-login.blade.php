@extends('layouts.app2')

@section('css')
<link rel="stylesheet" href="{{ asset('css/staff-login.css') }}">
@endsection

@section('title', 'スタッフログイン')

@section('content')
<main class="main">
    <div class="card">
        <div class="card-header">Staff Login</div>
        <div class="card-body">
            <form method="POST" action="{{ route('staff.login.submit') }}" novalidate>
                @csrf
                <div class="input-group">
                    <span class="icon">✉️</span>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" autofocus>
                </div>
                @if ($errors->has('email'))
                    @foreach ($errors->get('email') as $message)
                        <div class="error">{{ $message }}</div>
                    @endforeach
                @endif

                <div class="input-group">
                    <span class="icon">🔒</span>
                    <input type="password" name="password" placeholder="Password">
                </div>
                @if ($errors->has('password'))
                    @foreach ($errors->get('password') as $message)
                        <div class="error">{{ $message }}</div>
                    @endforeach
                @endif

                <div class="button-area">
                    <button type="submit" class="btn-submit">ログイン</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection