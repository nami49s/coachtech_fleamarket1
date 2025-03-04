<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user ? $user->profile : null;

        // URLの `tab` パラメータを取得（デフォルトは 'selling'）
        $tab = $request->query('tab', 'selling');

        // 出品した商品（User モデルが items() リレーションを持っている場合）
        $sellingItems = $user->items ?? collect(); // リレーションが未定義でもエラーを防ぐ

        // 購入した商品（purchases() リレーションがある前提だが、テーブルがなくてもエラー回避）
        $purchasedItems = method_exists($user, 'purchases') ? $user->purchases : collect();

        return view('mypage', compact('profile', 'tab', 'sellingItems', 'purchasedItems'));
    }
}
