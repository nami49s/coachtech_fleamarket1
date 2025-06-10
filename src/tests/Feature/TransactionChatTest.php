<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ChatMessage;

class TransactionChatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function マイページで取引中の商品が表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'status' => Item::STATUS_IN_TRANSACTION,
        ]);

        $this->actingAs($user)
                ->get(route('mypage'))
                ->assertSee($item->name);
    }

    /** @test */
    public function 取引中の商品に未読メッセージ件数が表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id, 'status' => Item::STATUS_IN_TRANSACTION]);

        ChatMessage::factory()->count(3)->create([
            'item_id' => $item->id,
            'user_id' => User::factory()->create()->id,
            'is_read' => false,
        ]);

        $this->actingAs($user)
                ->get(route('mypage'))
                ->assertSee('3');
    }

    /** @test */
    public function 商品をクリックするとチャット画面へ遷移する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['status' => Item::STATUS_IN_TRANSACTION]);
        $item->buyer_id = $user->id;
        $item->save();

        $this->actingAs($user)
                ->get(route('chat.show', ['item' => $item->id]))
                ->assertStatus(200)
                ->assertSee($item->name);
    }

    /** @test */
    public function サイドバーで別の取引チャットに遷移できる()
    {
        $user = User::factory()->create();

        $itemA = Item::factory()->create(['buyer_id' => $user->id, 'status' => Item::STATUS_IN_TRANSACTION]);
        $itemB = Item::factory()->create(['buyer_id' => $user->id, 'status' => Item::STATUS_IN_TRANSACTION]);

        $this->actingAs($user)
                ->get(route('chat.show', ['item' => $itemA->id]))
                ->assertSee($itemB->name);
    }

    /** @test */
    public function 最新メッセージ順で商品が並ぶ()
    {
        $user = User::factory()->create();

        $item1 = Item::factory()->create(['buyer_id' => $user->id, 'status' => Item::STATUS_IN_TRANSACTION]);
        $item2 = Item::factory()->create(['buyer_id' => $user->id, 'status' => Item::STATUS_IN_TRANSACTION]);

        ChatMessage::factory()->create(['item_id' => $item1->id, 'created_at' => now()->subDay()]);
        ChatMessage::factory()->create(['item_id' => $item2->id, 'created_at' => now()]);

        $response = $this->actingAs($user)->get(route('mypage'));
        $response->assertSeeInOrder([$item2->name, $item1->name]);
    }

    /** @test */
    public function 未読メッセージがある商品に通知マークが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['buyer_id' => $user->id, 'status' => Item::STATUS_IN_TRANSACTION]);

        ChatMessage::factory()->create([
            'item_id' => $item->id,
            'user_id' => User::factory()->create()->id,
            'is_read' => false,
        ]);

        $this->actingAs($user)
                ->get(route('mypage'))
                ->assertSee('1');
    }
}
