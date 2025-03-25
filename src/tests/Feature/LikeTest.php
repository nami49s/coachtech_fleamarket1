<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねアイコンを押下するといいねが登録される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('items.like', ['item' => $item->id]));

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいね済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user->likedItems()->attach($item->id);

        $response = $this->actingAs($user)->get(route('item.detail', ['item' => $item->id]));
        $response->assertSee('liked');
    }

    /** @test */
    public function いいねアイコンを再度押下するといいねが解除される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user->likedItems()->attach($item->id);

        $this->actingAs($user)
            ->post(route('items.like', ['item' => $item->id]))
            ->assertStatus(302);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
