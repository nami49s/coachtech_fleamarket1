<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        Auth::loginUsingId($user->id);
    }

    /** @test */
    public function 全商品を取得できる()
    {
        $user = User::factory()->create();

        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get(route('top', ['tab' => 'recommended']));

        $response->assertStatus(200);
        $this->assertEquals(3, Item::count());

        foreach ($items as $item) {
            $response->assertDontSee($item->name);
        }
    }

    /** @test */
    public function 購入済み商品は_SOLD_と表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('top'));

        $response->assertSee('SOLD');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->get(route('top'));

        $response->assertSee($item->name);
    }
}
