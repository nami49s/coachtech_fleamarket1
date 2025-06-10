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
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $seller = User::factory()->create();

        $item = Item::factory()->create(['user_id' => $seller->id]);

        $this->post('/purchase/payment', [
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
        ])->assertStatus(302);

        $response = $this->followingRedirects()->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('カード支払い');
    }

    /** @test */
    public function 支払い方法をコンビニ払いに変更すると即時反映される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $seller = User::factory()->create();

        $item = Item::factory()->create(['user_id' => $seller->id]);

        $this->post('/purchase/payment', [
            'item_id' => $item->id,
            'payment_method' => 'コンビニ払い',
        ])->assertStatus(302);

        $response = $this->followingRedirects()->get('/purchase/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');
    }
}