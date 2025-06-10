<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\UserRating;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompletedMail;

class UserRatingController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'ratee_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rater = auth()->user();
        $ratee = $request->ratee_id;

        $exists = UserRating::where([
            ['rater_id', $rater->id],
            ['ratee_id', $ratee],
            ['item_id', $item->id],
        ])->exists();

        if ($exists) {
            return back()->with('error', 'すでに評価済みです');
        }

        UserRating::create([
            'rater_id' => $rater->id,
            'ratee_id' => $ratee,
            'item_id' => $item->id,
            'rating' => $request->rating,
        ]);

        $ratingCount = UserRating::where('item_id', $item->id)->count();

        if ($ratingCount >= 2) {
            $item->status = 'completed';
            $item->save();
        }

        if ($rater->id === $item->buyer_id && $ratee == $item->user_id) {
            $user = User::find($item->user_id);
            Mail::to($user->email)->send(new TransactionCompletedMail($item));
        }

        return redirect()->route('top')->with('success', '評価を送信しました');
    }
}
