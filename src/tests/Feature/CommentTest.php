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

        protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'comment' => 'これはテストコメントです。',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです。',
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
    }

    /** @test */
    public function コメントが255字以上の場合バリデーションが表示される()
    {
        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);

        $response = $this->post(route('comments.store', ['item' => $item->id]), [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment']);
    }
}
