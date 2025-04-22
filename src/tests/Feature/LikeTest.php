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

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function いいねアイコンを押下するといいねが登録される()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('items.like', ['item' => $item->id]));

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいね済みのアイコンは色が変化する()
    {
        $item = Item::factory()->create();
        $this->user->likedItems()->attach($item->id);

        $response = $this->get(route('item.detail', ['item' => $item->id]));
        $response->assertSee('liked');
    }

    /** @test */
    public function いいねアイコンを再度押下するといいねが解除される()
    {
        $item = Item::factory()->create();
        $this->user->likedItems()->attach($item->id);

        $this->post(route('items.like', ['item' => $item->id]))
            ->assertStatus(302);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);
    }
}
