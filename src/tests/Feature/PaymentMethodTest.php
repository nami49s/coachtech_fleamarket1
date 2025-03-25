<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Item;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法をクレジットカードに変更すると即時反映される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
        ]);

        session(['item_id' => $item->id]);

        $response = $this->post('/purchase/payment', [
            'payment_method' => 'credit-card',
        ]);

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'payment_method' => 'カード支払い',
        ]);

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    /** @test */
    public function 支払い方法をコンビニ払いに変更すると即時反映される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
        ]);

        session(['item_id' => $item->id]);

        $response = $this->post('/purchase/payment', [
            'payment_method' => 'convenience-store',
        ]);

        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'payment_method' => 'コンビニ払い',
        ]);

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');
    }
}