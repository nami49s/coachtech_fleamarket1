<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ChatMessage;
use App\Models\UserRating;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreChatMessageRequest;

class ChatController extends Controller
{
    public function show(Item $item)
    {
        $user = Auth::user();

        // アクセス権をチェック（出品者 or 購入者のみ許可）
        if ($item->user_id !== $user->id && $item->buyer_id !== $user->id) {
            abort(403, 'アクセス権がありません');
        }

        // ログイン中のユーザーが出品者かどうか
        $isSeller = $item->user_id === $user->id;

        // ★ 未読メッセージを既読にする
        ChatMessage::where('item_id', $item->id)
            ->where('user_id', '!=', $user->id)  // 自分が送ってないメッセージ（=自分が受信者）
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $tradingItems = Item::where('status', 'in_transaction')
            ->where('id', '!=', $item->id)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('buyer_id', $user->id);
            })
            ->get();
        $messages = ChatMessage::where('item_id', $item->id)
            ->with('user.profile') // プロフィールも取得（画像・名前用）
            ->orderBy('created_at')
            ->get();

        $alreadyRated = UserRating::where('item_id', $item->id)
            ->where('rater_id', $user->id)
            ->exists();

        $canRate = !$alreadyRated && ($item->user_id === $user->id || $item->buyer_id === $user->id);

        return view('chat', compact('item', 'isSeller', 'tradingItems', 'messages', 'canRate'));
    }

    public function complete(Request $request, Item $item)
    {
        if (auth()->id() !== $item->buyer_id) {
            abort(403, '購入者のみが完了できます');
        }

        // 完了処理（例：取引ステータス更新など）
        $item->status = 'completed'; // 'status' カラムがある場合
        $item->save();

        return redirect()->route('chat.show', ['item' => $item->id])
                        ->with('show_rating_modal', true);
    }

    public function store(StoreChatMessageRequest $request, Item $item)
    {
        $user = Auth::user();

        // アクセス権チェック
        if ($item->user_id !== $user->id && $item->buyer_id !== $user->id) {
            abort(403, 'アクセス権がありません');
        }

        $data = [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
            $data['image_path'] = $path;
        }

        if (!empty(trim($data['message'])) || !empty($data['image_path'])) {
            ChatMessage::create($data);
        }

    return redirect()->route('chat.show', $item->id);
    }

    public function update(StoreChatMessageRequest $request, ChatMessage $chatMessage)
    {
        $this->authorizeMessage($chatMessage);

        $chatMessage->message = $request->message;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
            $chatMessage->image_path = $path;
        }

        $chatMessage->save();

        return redirect()->route('chat.show', $chatMessage->item_id)
            ->with('status', 'メッセージを更新しました');
    }

    public function destroy(ChatMessage $chatMessage)
    {
        $this->authorizeMessage($chatMessage);

        $chatMessage->delete();

        return redirect()->route('chat.show', $chatMessage->item_id)
            ->with('status', 'メッセージを削除しました');
    }

    protected function authorizeMessage(ChatMessage $chatMessage)
    {
        if ($chatMessage->user_id !== Auth::id()) {
            abort(403, '自分のメッセージしか編集・削除できません');
        }
    }
}
