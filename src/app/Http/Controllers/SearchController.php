<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        // 検索キーワードを取得
        $query = $request->input('query');
        $items = collect();

        // 検索条件を適用して商品を取得
        if (!empty($query)) {
            $items = Item::where('user_id', '!=', Auth::id()) // 自分の商品を除外
                        ->where(function ($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%");
                        })
                        ->get();
        } else {
            // 検索キーワードがない場合は、全商品（自分以外）を取得
            $items = Item::where('user_id', '!=', Auth::id())->get();
        }

        return view('top', [
            'items' => $items,
            'query' => $query,
            'tab' => 'recommended'
        ]);
    }
}
