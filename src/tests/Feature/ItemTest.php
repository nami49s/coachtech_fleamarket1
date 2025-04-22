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

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function 全商品を取得できる()
    {
        $items = Item::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('top', ['tab' => 'recommended']));

        $response->assertStatus(200);
        $this->assertEquals(3, Item::count());

        foreach ($items as $item) {
            $response->assertDontSee($item->name);
        }
    }

    /** @test */
    public function 購入済み商品は_SOLD_と表示される()
    {
        $itemOwner = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $itemOwner->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('top', ['tab' => 'recommended']));

        $response->assertSee('SOLD');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $item = Item::factory()->create(['user_id' => $this->user->id]);

        $otherUser = User::factory()->create();
        $response = $this->actingAs($otherUser)->get(route('top'));

        $response->assertSee($item->name);
    }
}
