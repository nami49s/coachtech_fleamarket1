<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function show($itemId)
    {
        $item = Item::where('id', $itemId)->first();
        $user = auth()->user();
        $profile = $user ? $user->profile : null;

        session(['item_id' => $itemId]);

        return view('purchase', compact('item', 'profile'));
    }

    public function editAddress()
    {
        if (!session()->has('item_id')) {
            return redirect()->route('mypage')->with('error', '商品情報が見つかりません');
        }

        $user = auth()->user();
        $profile = $user->profile;

        return view('edit_address', compact('profile'));
    }

    public function updateAddress(UpdateAddressRequest $request)
    {
        session([
            'shipping_postal_code' => $request->postal_code,
            'shipping_address' => $request->address,
            'shipping_building' => $request->building,
        ]);

        $itemId = session('item_id');

        if (!$itemId) {
            \Log::error('商品情報が見つかりません');
            return redirect()->route('mypage')->with('error', '商品情報が見つかりません');
        }

        \Log::info('session item_id:', ['item_id' => $itemId]);

        return redirect()->route('purchase.show', ['item' => $itemId])->with('success', '配送先を更新しました。');
    }

    public function store(Request $request, Item $item)
    {

        if ($item->is_sold) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

        $profile = Auth::user()->profile;

        $postal_code = session('shipping_postal_code') ?? $profile->postal_code;
        $address = session('shipping_address') ?? $profile->address;
        $building = session('shipping_building') ?? $profile->building;

        $payment_method = $request->input('payment_method');

        if (!$payment_method) {
            Log::error('支払い方法が送信されていません！');
            return redirect()->back()->with('error', '支払い方法を選択してください。');
    }


        DB::transaction(function () use ($item, $postal_code, $address, $building, $payment_method) {
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'postal_code' => $postal_code,
                'address' => $address,
                'building' => $building,
                'payment_method' => $payment_method,
            ]);

            $item->update(['is_sold' => true]);

        });

        session()->forget(['shipping_postal_code', 'shipping_address', 'shipping_building']);

        return redirect()->route('mypage')->with('success', '購入が完了しました！');
    }

}
