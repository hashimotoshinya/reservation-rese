<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationStatusController extends Controller
{
    public function index(Shop $shop)
    {
        $this->authorize('view', $shop);

        $today = Carbon::today();

        $todayReservations = $shop->reservations()
            ->whereDate('date', $today)
            ->orderBy('time')
            ->get();

        $futureReservations = $shop->reservations()
            ->whereDate('date', '>', $today)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $pastReservations = $shop->reservations()
            ->whereDate('date', '<', $today)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        return view('staff.reservation-status', compact(
            'shop',
            'todayReservations',
            'futureReservations',
            'pastReservations'
        ));
    }
}
