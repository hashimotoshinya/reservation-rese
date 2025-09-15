<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOwnerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NewOwnerController extends Controller
{
    public function store(StoreOwnerRequest $request)
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'owner',
        ]);

        return redirect()->back()->with('success', '店舗代表者を作成しました。');
    }
}