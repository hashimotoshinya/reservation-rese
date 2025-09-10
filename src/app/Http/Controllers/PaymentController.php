<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => 'ダミー予約決済',
                    ],
                    'unit_amount' => 5000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('mypage.index') . '?success=true',
            'cancel_url' => route('mypage.index') . '?canceled=true',
        ]);

        return redirect($session->url);
    }
}