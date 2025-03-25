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

        $item = Item::find(session('item_id'));

        return redirect()->route('purchase.show', ['item' => $item->id])->with('success', '配送先を更新しました。');
    }

    public function store(Request $request, Item $item)
    {

        if ($item->is_sold) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

        $user = auth()->user();

        // セッションから送付先情報を取得
        $postal_code = session('shipping_postal_code');
        $address = session('shipping_address');
        $building = session('shipping_building');

        // 購入情報をデータベースに保存
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => $postal_code,
            'address' => $address,
            'building' => $building,
            'payment_method' => $request->payment_method,
        ]);

        // 商品の購入済みステータスを更新
        $item->is_sold = true;
        $item->save();

        $stripeController = new StripeController();
        return $stripeController->checkout(new Request(['item_id' => $item->id, 'payment_method' => 'credit-card']));
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:credit-card,convenience-store',
        ]);


        session(['payment_method' => $request->payment_method]);

        $purchase = Purchase::where('user_id', auth()->id())
            ->latest()
            ->first();

        // 支払い方法を更新
        if ($purchase) {
            $purchase->payment_method = $request->payment_method == 'credit-card' 
                ? 'カード支払い' 
                : 'コンビニ払い';
            $purchase->save();
        }

        return redirect()->back();
    }

}
