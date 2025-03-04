<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExhibitionController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validatedExhibitionData = $request->validated();
        $validatedExhibitionData['user_id'] = auth()->id();

        $item = Item::create($validatedExhibitionData);

        if ($request->hasFile('item_image')) { // フォームの `name` に合わせる
            $path = $request->file('item_image')->store('items', 'public'); // 正しい保存パス
            $item->update(['item_image' => $path]); // ここで画像を保存
        }

        return redirect()->route('mypage')->with('success', '出品が完了しました');
    }

    public function index(Request $request)
    {
        {
            $tab = $request->query('tab', 'recommended'); // デフォルトは "recommended"

            if ($tab === 'recommended') {
                // 「おすすめ」タブでは全商品のみを表示（未ログインでも表示可能）
                $items = Item::all();
            } elseif ($tab === 'mylist') {
            if (auth()->check()) {
                // 「マイリスト」タブではログインユーザーの商品を取得
                $items = Item::where('user_id', auth()->id())->get();
            } else {
                // 未ログインで「マイリスト」タブを開いた場合はログインページへリダイレクト
                return redirect()->route('login')->with('error', 'ログインが必要です');
            }
        } else {
            // タブが不明な場合はデフォルトの動作
            $items = collect();
        }
            return view('top', compact('items', 'tab'));
        }
    }

    public function sellingItems()
    {
        if (!auth()->check()) {
            return response()->json([]); // 未ログイン時は空の配列を返す
        }

        $items = Item::where('user_id', Auth::id())->get();

        return response()->json($items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'item_image' => $item->item_image ? url('storage/' . $item->item_image) : null,
            ];
        }));
    }

    public function show(Item $item)
    {
        $likesCount = auth()->check() ? $item->likes()->count() : 0;
        return view('detail', compact('item', 'likesCount'));
    }
}