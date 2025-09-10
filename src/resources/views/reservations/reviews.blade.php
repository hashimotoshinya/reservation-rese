@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
<div class="review-container">
    <h2 class="review-title">レビュー投稿 - {{ $reservation->shop->name }}</h2>

    <form action="{{ route('reviews.store', $reservation->id) }}" method="POST" class="review-form">
        @csrf

        {{-- 評価（星） --}}
        <div class="form-group">
            <label>評価</label>
            <div class="star-rating">
                @for ($i = 5; $i >= 1; $i--) {{-- ★5を左、★1を右にする --}}
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                    <label for="star{{ $i }}">★</label>
                @endfor
            </div>
            @error('rating')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        {{-- コメント --}}
        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea name="comment" id="comment" rows="4" class="form-control"></textarea>
            @error('comment')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">投稿する</button>
        </div>
    </form>
</div>
@endsection