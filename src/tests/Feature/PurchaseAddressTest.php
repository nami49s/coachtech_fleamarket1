<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseAddressTest extends TestCase
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
    public function 送付先住所が商品購入画面に正しく反映される()
    {
        $item = Item::factory()->create();

        session(['item_id' => $item->id]);

        $response = $this->post(route('update_address'), [
            'postal_code' => '123-4567',
            'address' => '東京都新宿区2-2-2',
            'building' => 'サンプルマンション202'
        ]);

        $response->assertRedirect(route('purchase.show', ['item' => $item->id]));

        $item = Item::factory()->create();
        $response = $this->get(route('purchase.show', ['item' => $item->id]));
        $response->assertStatus(200);
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        Profile::factory()->create([
            'user_id' => $this->user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101'
        ]);

        $item = Item::factory()->create();

        $this->post(route('update_address'), [
            'postal_code' => '987-6543',
            'address' => '東京都新宿区2-2-2',
            'building' => 'サンプルマンション202'
        ]);

        $response = $this->post(route('purchase.store', ['item' => $item->id]), [
            'payment_method' => 'credit_card'
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'postal_code' => '987-6543',
            'address' => '東京都新宿区2-2-2',
            'building' => 'サンプルマンション202'
        ]);
    }
}
