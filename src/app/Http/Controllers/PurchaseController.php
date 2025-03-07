<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateAddressRequest;

class PurchaseController extends Controller
{
    public function show($itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = auth()->user();
        $profile = $user->profile;

        return view('purchase', compact('item', 'profile'));
    }

    public function editAddress()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('edit_address', compact('profile'));
    }

    public function updateAddress(UpdateAddressRequest $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        if ($profile) {
            // 既存のプロフィールを更新
            $profile->update($request->validated());
        } else {
            // プロフィールがない場合、新規作成
            $user->profile()->create($request->validated());
        }

        return redirect()->route('edit_address')->with('success', '配送先を更新しました。');
    }

    public function store(Item $item)
    {
        if ($item->is_sold) {
            return redirect()->back()->with('error', 'この商品はすでに購入されています。');
        }

        Purchase::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
        ]);

        $item->update(['is_sold => true']);

        return redirect()->route('mypage')->with('success', '購入が完了しました！');
    }

}
