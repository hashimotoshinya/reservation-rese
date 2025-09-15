@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop-edit.css') }}">
@endsection

@section('content')
<div class="container shop-edit-page">
    <h1>
        @if(isset($shop))
            <a href="{{ url('/staff/owner') }}" class="back-btn">&lt;</a>
            店舗情報を編集
        @else
            <a href="{{ url('/staff/owner') }}" class="back-btn">&lt;</a>
            新しい店舗を登録
        @endif
    </h1>

    @if(isset($shop))
        {{-- 更新フォーム --}}
        <form method="POST" action="{{ route('owner.shop.update', $shop) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    @else
        {{-- 新規登録フォーム --}}
        <form method="POST" action="{{ route('owner.shop.store') }}" enctype="multipart/form-data">
            @csrf
    @endif

        <div>
            <label>店舗名</label>
            <input type="text" name="name" value="{{ old('name', $shop->name ?? '') }}">
            @error('name') <div style="color:red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>エリア</label>
            <select name="area_id">
                <option value="">選択してください</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}"
                        {{ old('area_id', $shop->area_id ?? '') == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            @error('area_id') <div style="color:red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>ジャンル</label>
            <select name="genre_id">
                <option value="">選択してください</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}"
                        {{ old('genre_id', $shop->genre_id ?? '') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
            @error('genre_id') <div style="color:red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>店舗説明</label>
            <textarea name="description">{{ old('description', $shop->description ?? '') }}</textarea>
            @error('description') <div style="color:red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>店舗画像</label>
            <input type="file" name="image" id="image-input">
            @error('image') <div style="color:red;">{{ $message }}</div> @enderror

            {{-- 既存画像がある場合 --}}
            @if(isset($shop) && $shop->image_path)
                <p>現在の画像:</p>
                <img src="{{ asset('storage/' . $shop->image_path) }}" alt="店舗画像" style="max-width:200px;">
            @endif

            {{-- プレビュー --}}
            <div id="preview-container" style="margin-top:10px;">
                <p id="preview-title" style="display:none;">選択した画像プレビュー:</p>
                <img id="image-preview" src="#" alt="プレビュー" style="max-width:200px; display:none;">
            </div>
        </div>

        <button type="submit">
            @if(isset($shop))
                更新する
            @else
                登録する
            @endif
        </button>
    </form>
</div>

<script>
document.getElementById('image-input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewTitle = document.getElementById('preview-title');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            previewTitle.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        previewTitle.style.display = 'none';
    }
});
</script>
@endsection