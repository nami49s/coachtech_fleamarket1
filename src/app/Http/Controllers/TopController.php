<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class TopController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user ? $user->profile : null;
        $tab = $request->query('tab', 'recommended');

        if ($tab === 'recommended') {
            if ($user) {
                $items = Item::where('user_id', '!=', $user->id)->get();
            } else {
                $items = Item::all(); // 未ログイン時は全商品を取得
            }
        }
        elseif ($tab === 'mylist') {
            $items = collect();
        }
        else {
            $items = collect();
        }

        return view('top', compact('profile', 'tab', 'items'));
    }
}
