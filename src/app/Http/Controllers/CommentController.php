<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $itemId)
    {
        Comment::create([
            'user_id' => Auth()->id(),
            'item_id' => $itemId,
            'comment' => $request->comment,
        ]);

        return back()->with('success','コメントを投稿しました！');
    }
}
