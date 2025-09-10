<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create(Reservation $reservation)
    {
        return view('reservations.reviews', compact('reservation'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        $reservation->review()->create([
            'user_id' => auth()->id(),
            'shop_id' => $reservation->shop_id,
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('mypage.index')->with('success', 'レビューを投稿しました！');
    }
}
