@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="edit-container">
    <div class="edit-card">
        <h2>予約変更</h2>

        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>日付</label>
            <input type="date" name="date"
                value="{{ old('date', \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')) }}">
            @error('date')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label>時間</label>
            <select name="time">
                <option value="">-- 時間を選択してください --</option>
                @for ($hour = 0; $hour < 24; $hour++)
                    @php
                        $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                    @endphp
                    <option value="{{ $formattedHour }}"
                        {{ old('time', \Carbon\Carbon::parse($reservation->time)->format('H:i')) == $formattedHour ? 'selected' : '' }}>
                        {{ $formattedHour }}
                    </option>
                @endfor
            </select>
            @error('time')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label>人数</label>
            <select name="number">
                <option value="">-- 人数を選択してください --</option>
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('number', $reservation->number) == $i ? 'selected' : '' }}>
                        {{ $i }}人
                    </option>
                @endfor
            </select>
            @error('number')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <button type="submit" class="edit-btn">変更する</button>
        </form>
        @if($reservation->status === 'active')
            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="cancel-btn">キャンセルする</button>
            </form>
        @endif
    </div>
</div>
@endsection
