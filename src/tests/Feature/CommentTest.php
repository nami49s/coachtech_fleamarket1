<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'comment' => 'これはテストコメントです。',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです。',
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    /** @test */
    public function コメントが255字以上の場合バリデーションが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longComment = str_repeat('あ', 256);

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
