<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopEditRequest;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopEditController extends Controller
{
    public function create()
    {
        $areas  = Area::all();
        $genres = Genre::all();

        return view('staff.shop-edit', compact('areas', 'genres'));
    }

    public function store(ShopEditRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('image')
            ? $request->file('image')->store('images', 'public')
            : null;

        Auth::user()->shops()->create([
            'name'        => $validated['name'],
            'area_id'     => $validated['area_id'],
            'genre_id'    => $validated['genre_id'],
            'description' => $validated['description'],
            'image_path'  => $path,
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', '店舗を新規登録しました。');
    }

    public function edit(Shop $shop)
    {
        $this->authorize('update', $shop);

        $areas  = Area::all();
        $genres = Genre::all();

        return view('staff.shop-edit', compact('shop', 'areas', 'genres'));
    }

    public function update(ShopEditRequest $request, Shop $shop)
    {
        $this->authorize('update', $shop);

        $validated = $request->validated();
        $data = $validated;

        if ($request->hasFile('image')) {
            if ($shop->image_path) {
                Storage::disk('public')->delete($shop->image_path);
            }
            $data['image_path'] = $request->file('image')->store('images', 'public');
        }

        $shop->update($data);

        return redirect()->route('owner.dashboard')
            ->with('success', '店舗情報を更新しました。');
    }
}