<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Checkout\Session as StripeSession;
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
        $item = Item::factory()->create(['is_sold' => false]);

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
        $item = Item::factory()->create(['is_sold' => false]);

        $response = $this->get(route('purchase.success', ['item' => $item->id]));

        $item->refresh();
        $this->assertTrue($item->is_sold);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect(route('mypage'));
        $response->assertSessionHas('success', '購入が完了しました！');
    }

    /** @test */
    public function 購入した商品は商品一覧画面でSOLDと表示される()
    {
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'is_sold' => false
        ]);

        $this->post(route('purchase.store', ['item' => $item->id]));
        $this->get(route('purchase.success', ['item' => $item->id]));

        $this->assertTrue(Item::find($item->id)->is_sold);

        $response = $this->get(route('top'));

        $response->assertSee($item->name);
        $response->assertSee('SOLD');
    }

    /** @test */
    public function プロフィールの購入した商品一覧に追加される()
    {
        $item = Item::factory()->create(['is_sold' => false]);

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

        $response = $this->actingAs($this->user)->get('/mypage?tab=purchased');
        $response->assertSee($item->name);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
