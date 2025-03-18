<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;

class StripeController extends Controller
{
    public function showCheckoutForm($id)
    {
        $item = Item::findOrFail($id);
        return view('checkout', compact('item'));
    }
    public function checkout(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'payment_method' => 'required|string',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->payment_method === 'credit-card') {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            session(['payment_method' => 'credit-card']);

            // Checkoutセッションの作成
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'], // 支払い方法（デフォルトでカードが有効）
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy', // 日本円
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price, // 価格（1000円）
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment', // 一回払い
                'success_url' => route('purchase.success' , ['item' => $item->id]), // 成功時のリダイレクト先
                'cancel_url' => route('mypage') . '?payment=cancel',   // キャンセル時のリダイレクト先
            ]);

            // Stripeの決済ページにリダイレクト
            return response()->json(['session_id' => $session->id]);
        }

        return response()->json(['error' => '対応していない支払い方法です'], 400);
    }

    public function konbiniCheckout(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $item = Item::findOrFail($request->item_id);

        if (!auth()->check()) {
            return response()->json(['error' => 'ログインが必要です'], 401);
        }

        session(['payment_method' => 'konbini']);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price, // 円からセン単位へ
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => auth()->user()->email,
            'success_url' => route('purchase.success', ['item' => $item->id]),
            'cancel_url' => url()->previous(),
        ]);

        return response()->json(['session_id' => $session->id]);
    }

    public function success(Item $item)
    {
        if ($item->is_sold) {
            return redirect()->route('mypage')->with('error', 'この商品はすでに購入されています。');
        }

        $profile = Auth::user()->profile;

        $paymentMethod = session('payment_method', 'credit-card');

        DB::transaction(function () use ($item, $profile, $paymentMethod) {
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'postal_code' => session('shipping_postal_code') ?? $profile->postal_code,
                'address' => session('shipping_address') ?? $profile->address,
                'building' => session('shipping_building') ?? $profile->building,
                'payment_method' => $paymentMethod, // Stripeはカード決済なので固定
            ]);

            $item->update(['is_sold' => true]);
        });

        session()->forget(['shipping_postal_code', 'shipping_address', 'shipping_building', 'payment_method']);

        return redirect()->route('mypage')->with('success', '購入が完了しました！');
    }
}
