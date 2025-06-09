<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function 他人の商品は表示され自分の商品は表示されない()
    {
        $otherUser = User::factory()->create();
        $visibleItems = Item::factory()->count(3)->create([
            'user_id' => $otherUser->id,
        ]);

        $hiddenItem = Item::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('top', ['tab' => 'recommended']));

        foreach ($visibleItems as $item) {
            $response->assertSee($item->name);
        }

        $response->assertDontSee($hiddenItem->name);
    }

    /** @test */
    public function 購入済み商品は_sold_と表示される()
    {
        $itemOwner = User::factory()->create();

        // 商品のステータスを「購入済み」に設定
        $item = Item::factory()->create([
            'user_id' => $itemOwner->id,
            'status' => Item::STATUS_COMPLETED, // ← 明示的に SOLD 状態に
        ]);

        // このユーザーが購入したことにする
        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        // 商品一覧ページへアクセス
        $response = $this->actingAs($this->user)->get(route('top', ['tab' => 'recommended']));

        // 商品名が表示され、「SOLD」と表記されていることを確認
        $response->assertSee($item->name);
        $response->assertSee('SOLD');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('top', ['tab' => 'recommended']));

        $response->assertDontSee($item->name);
    }
}