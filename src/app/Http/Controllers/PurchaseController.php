<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
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
