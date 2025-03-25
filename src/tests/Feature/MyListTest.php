<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create();
        $notLikedItem = Item::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('top', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertSee($likedItem->name);

        $response->assertDontSee($notLikedItem->name);
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

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get(route('top', ['tab' => 'mylist']));

        $response->assertSee('SOLD');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        $myItem = Item::factory()->create(['user_id' => $user->id]);

        $otherItem = Item::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $otherItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('top', ['tab' => 'mylist']));

        $response->assertStatus(200);

        $response->assertDontSee($myItem->name);

        $response->assertSee($otherItem->name);
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $item = Item::factory()->create();

        Like::factory()->create(['item_id' => $item->id]);

        $response = $this->get(route('top', ['tab' => 'mylist']));

        $response->assertDontSee($item->name);
    }
}
