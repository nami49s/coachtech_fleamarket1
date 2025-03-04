<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;

class ItemController extends Controller
{
    public function like(Item $item)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }

        $user = auth()->user();

        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        return back();
    }
}
