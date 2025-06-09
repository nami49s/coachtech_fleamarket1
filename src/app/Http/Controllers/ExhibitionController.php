<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
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

        unset($validatedExhibitionData['category_id']);

        $item = Item::create($validatedExhibitionData);

        if ($request->hasFile('item_image')) {
            $path = $request->file('item_image')->store('item_images', 'public');
            $item->update(['item_image' => $path]);
        }

        if ($request->has('category_ids')) {
            $item->categories()->attach($request->category_ids);
        }

        return redirect()->route('mypage')->with('success', '出品が完了しました');
    }

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommended');
        $query = $request->input('query');
        $user = Auth::user();

        if ($tab === 'mylist') {
            $items = $user ? $user->likes()->with('purchases')->latest()->get() : collect();
        } else {
            $itemsQuery = Item::with('user', 'purchases')
                ->whereNull('deleted_at');

            // 自分の商品を除外（ログイン時のみ）
            if ($user) {
                $itemsQuery->where('user_id', '!=', $user->id);
            }

            // クエリがある場合は商品名で検索
            if ($query) {
                $itemsQuery->where('name', 'like', '%' . $query . '%');
            }

            $items = $itemsQuery->latest()->get();
        }

        return view('top', compact('items', 'tab'));
    }

    public function sellingItems()
    {
        if (!auth()->check()) {
            return response()->json([]);
        }

        $items = Item::where('user_id', Auth::id())->with('categories')->get();

        return response()->json($items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'item_image' => $item->item_image ? url('storage/' . $item->item_image) : null,
                'categories' => $item->categories->pluck('name'),
            ];
        }));
    }

    public function show(Item $item)
    {
        $item->load('user', 'categories');
        $likesCount = $item->likes()->count();
        return view('detail', compact('item', 'likesCount'));
    }
}