<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $reservation = auth()->user()->reservations()->create([
            'shop_id' => $request->shop_id,
            'date'    => $request->date,
            'time'    => $request->time,
            'number'  => $request->number,
        ]);


        return redirect()->route('done', ['shop_id' => $reservation->shop_id]);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('mypage.index')->with('success', '予約をキャンセルしました');
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.edit', compact('reservation'));
    }

    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->date = $request->date;
        $reservation->time = $request->time;
        $reservation->number = $request->number;
        $reservation->save();

        return redirect()->route('mypage.index')->with('success', '予約を変更しました');
    }

    public function verify($token)
    {
        $reservation = Reservation::where('qr_token', $token)->firstOrFail();

        return view('reservations.verify', compact('reservation'));
    }

    public function complete($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'completed';
        $reservation->save();

        return redirect()->route('mypage.index')->with('success', '予約を完了しました');
    }
}
