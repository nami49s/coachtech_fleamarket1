<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Item;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user ? $user->profile : null;

        $tab = $request->query('tab', 'selling');

        $sellingItems = $user->items ?? collect();

        $purchasedItems = Item::where('buyer_id', $user->id)
                          ->where('status', 'completed')
                          ->get();

        $inTransactionItems = Item::where('status', 'in_transaction')
        ->where(function ($query) use ($user) {
            $query->where('items.user_id', $user->id)
                  ->orWhere('items.buyer_id', $user->id);
        })
        ->leftJoin('chat_messages', 'items.id', '=', 'chat_messages.item_id')
        ->select('items.*', DB::raw('MAX(chat_messages.created_at) as latest_message_time'))
        ->groupBy('items.id')
        ->orderByDesc('latest_message_time')
        ->get();

        foreach ($inTransactionItems as $item) {
            $item->unread_count = ChatMessage::where('item_id', $item->id)
                ->where('user_id', '!=', $user->id) // 相手からのメッセージ
                ->where('is_read', 0)
                ->count();
        }


        $unreadMessageCount = ChatMessage::whereIn('item_id', $inTransactionItems->pluck('id'))
            ->where('is_read', false)
            ->where('user_id', '!=', $user->id) // 自分が送信したものではない
            ->count();

        return view('mypage', compact('user', 'profile', 'tab', 'sellingItems', 'purchasedItems', 'inTransactionItems', 'unreadMessageCount'));
    }
}
