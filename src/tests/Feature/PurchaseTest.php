<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Mockery;

class PurchaseTest extends TestCase
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
    public function 購入するボタンを押下でStripeにリダイレクトされる()
    {
        $item = Item::factory()->create(['status' => 'active']);

        $mockSession = Mockery::mock('overload:' . \Stripe\Checkout\Session::class);
        $mockSession->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://test.stripe.com/checkout']);

        $response = $this->postJson(route('purchase.store', ['item' => $item->id]), [
            'item_id' => $item->id,
            'payment_method' => 'credit-card'
        ]);

        $response->assertRedirect('https://test.stripe.com/checkout');
    }

    /** @test */
    public function Stripe決済完了後に購入処理が完了する()
    {
        $item = Item::factory()->create(['status' => 'active']);

        $response = $this->get(route('purchase.success', ['item' => $item->id]));

        $item->refresh();
        $this->assertEquals('in_transaction', $item->status);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('success', '購入が完了しました！');
    }


    /** @test */
    public function プロフィールの取引中の商品一覧に追加される()
    {
        $item = Item::factory()->create(['status' => 'active']);

        $this->post(route('purchase.store', ['item' => $item->id]));
        $this->get(route('purchase.success', ['item' => $item->id]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        $this->user->refresh();

        $this->assertTrue($this->user->purchases->contains(function ($purchase) use ($item) {
            return $purchase->item_id === $item->id;
        }));

        $response = $this->actingAs($this->user)->get('/mypage?tab=in_transaction');
        $response->assertSee($item->name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}