<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $owner = Auth::user();
        $shops = $owner->shops()->get();

        return view('staff.owner', compact('shops'));
    }
}