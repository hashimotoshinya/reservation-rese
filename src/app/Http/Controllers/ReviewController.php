<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Reservation;

class ReviewController extends Controller
{
    public function create(Reservation $reservation)
    {
        return view('reservations.reviews', compact('reservation'));
    }

    public function store(ReviewRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        $reservation->review()->create([
            'user_id' => auth()->id(),
            'shop_id' => $reservation->shop_id,
            'rating'  => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('mypage.index')->with('success', 'レビューを投稿しました！');
    }
}