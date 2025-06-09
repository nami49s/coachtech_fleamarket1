<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法をクレジットカードに変更すると即時反映される()
    {
        // 認証済みユーザー
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        // 出品者は別ユーザーにする
        $seller = User::factory()->create();

        // 出品者の商品の作成
        $item = Item::factory()->create(['user_id' => $seller->id]);

        // 支払い方法を「カード支払い」に更新
        $this->post('/purchase/payment', [
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
        ])->assertStatus(302); // リダイレクトの確認（必要なら）

        // 商品購入画面で支払い方法が反映されているか確認
        $response = $this->followingRedirects()->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    /** @test */
    public function 支払い方法をコンビニ払いに変更すると即時反映される()
    {
        // 認証済みユーザー（メール認証も完了）
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        // 購入者とは別の出品者を作成
        $seller = User::factory()->create();

        // 出品者の商品を作成
        $item = Item::factory()->create(['user_id' => $seller->id]);

        // 支払い方法を「コンビニ払い」に更新
        $this->post('/purchase/payment', [
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
        ])->assertStatus(302);

        // 商品購入画面で支払い方法が反映されているか確認
        $response = $this->followingRedirects()->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');
    }
}