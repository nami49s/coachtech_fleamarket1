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

        $tab = $request->query('tab', 'selling');

        $sellingItems = $user->items ?? collect();

        $purchasedItems = $user->purchases()->with('item')->get()->pluck('item');

        return view('mypage', compact('profile', 'tab', 'sellingItems', 'purchasedItems'));
    }
}
