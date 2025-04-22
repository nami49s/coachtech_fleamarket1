<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->input('query');
        $tab = $request->input('tab', 'recommended');
        $items = collect();

        if (!empty($query)) {
            if ($tab === 'mylist' && Auth::check()) {
                $items = Auth::user()->likedItems()
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                    })
                    ->get();
            } else {
                $items = Item::where('user_id', '!=', Auth::id())
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                    })
                    ->get();
            }
        } else {
            if ($tab === 'mylist' && Auth::check()) {
                $items = Auth::user()->likedItems()->get();
            } else {
                $items = Item::where('user_id', '!=', Auth::id())->get();
            }
        }

        return view('top', [
            'items' => $items,
            'query' => $query,
            'tab' => $tab
        ]);
    }
}