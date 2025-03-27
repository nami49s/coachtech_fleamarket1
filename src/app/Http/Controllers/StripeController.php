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
        $user = auth()->user();

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        if ($request->payment_method === 'credit-card') {
            session(['payment_method' => 'credit-card']);
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('purchase.success' , ['item' => $item->id]),
                'cancel_url' => route('mypage') . '?payment=cancel',
            ]);

            return redirect($session->url);
        } elseif ($request->payment_method === 'convenience-store') {
            session(['payment_method' => 'コンビニ払い']);
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => auth()->user()->email,
                'success_url' => route('purchase.success', ['item' => $item->id]),
                'cancel_url' => route('mypage') . '?payment=cancel',
            ]);

            return redirect($session->url);
        }

        return redirect()->back()->with('error', '対応していない支払い方法です');

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
                'payment_method' => $paymentMethod,
            ]);

            $item->update(['is_sold' => true]);
        });

        session()->forget(['shipping_postal_code', 'shipping_address', 'shipping_building', 'payment_method']);

        return redirect()->route('mypage')->with('success', '購入が完了しました！');
    }
}
