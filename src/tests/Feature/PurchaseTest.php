<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Checkout\Session;
use Mockery;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入するボタンを押下でStripeにリダイレクトされる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $mockSession = Mockery::mock('overload:' . Session::class);
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)['id' => 'test_session_id']);

        $response = $this->actingAs($user)->postJson(route('purchase.store', ['item' => $item->id]));

        $response->assertStatus(200)
                ->assertJson(['session_id' => 'test_session_id']);
    }

    /** @test */
    public function Stripe決済完了後に購入処理が完了する()
    {
        $user = User::factory()->create(['id' => 1]);
        $item = Item::factory()->create(['is_sold' => false]);

        $response = $this->actingAs($user)->get(route('purchase.success', ['item' => $item->id]));

        $item->refresh();
        $this->assertTrue($item->is_sold);


        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('success', '購入が完了しました！');
    }

    /** @test */
    public function 購入した商品は商品一覧画面でSOLDと表示される()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false
        ]);

        $this->actingAs($buyer)->post(route('purchase.store', ['item' => $item->id]));
        $this->get(route('purchase.success', ['item' => $item->id]));

        $this->assertTrue(Item::find($item->id)->is_sold);

        $response = $this->actingAs($buyer)->get(route('top'));

        $response->assertSee($item->name);
        $response->assertSee('SOLD');
    }

    /** @test */
    public function プロフィールの購入した商品一覧に追加される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user)->post(route('purchase.store', ['item' => $item->id]));

        $this->get(route('purchase.success', ['item' => $item->id]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $user->refresh();

        $this->assertTrue($user->purchases->contains('item_id', $item->id));

        $response = $this->actingAs($user)->get(route('mypage'));
        $response->assertSee($item->name);
    }
}
