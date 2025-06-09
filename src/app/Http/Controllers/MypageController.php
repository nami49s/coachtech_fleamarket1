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

        // 出品した商品
        $sellingItems = Item::where('user_id', $user->id)->get();

        // 購入した商品（completedステータスのもの）
        $purchasedItems = Item::where('buyer_id', $user->id)
                          ->where('status', 'completed')
                          ->get();

        // 取引中の商品
        $inTransactionItems = Item::where('status', 'in_transaction')
            ->where(function ($query) use ($user) {
                $query->where('items.user_id', $user->id)
                    ->orWhere('items.buyer_id', $user->id);
            })
            ->addSelect([
                'latest_message_time' => ChatMessage::selectRaw('MAX(created_at)')
                    ->whereColumn('item_id', 'items.id'),
            ])
            ->orderByRaw('latest_message_time IS NULL, latest_message_time DESC')
            ->get();

        // 未読メッセージ数を追加
        foreach ($inTransactionItems as $item) {
            $item->unread_count = ChatMessage::where('item_id', $item->id)
                ->where('user_id', '!=', $user->id) // 相手からのメッセージ
                ->where('is_read', false)
                ->count();
        }

        $unreadMessageCount = ChatMessage::whereIn('item_id', $inTransactionItems->pluck('id'))
            ->where('is_read', false)
            ->where('user_id', '!=', $user->id) // 自分が送信したものではない
            ->count();

        return view('mypage', compact('user', 'profile', 'tab', 'sellingItems', 'purchasedItems', 'inTransactionItems', 'unreadMessageCount'));
    }
}
