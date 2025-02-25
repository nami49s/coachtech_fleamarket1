<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExhibitionController extends Controller
{
    public function create()
    {
        return view('sell');
    }

    public function store(ExhibitionRequest $request)
    {
        $validatedExhibitionData = $request->validated();

        $item = Item::create8($validatedExhibitionData);

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('item_images');

            $item->update(['image' => $path]);
        }
        return redirect()->route('mypage')->with('success', '出品が完了しました');
    }
}
