<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

class ItemDetailTest extends TestCase
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
    public function 詳細ページに必要な情報が表示される()
    {
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'これはテスト商品の説明です。',
            'condition' => '新品',
            'item_image' => 'item_images/test.jpg',
        ]);

        $category = Category::factory()->create();
        $item->categories()->attach($category->id);

        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです。',
        ]);

        $response = $this->get(route('item.detail', ['item' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1,000');
        $response->assertSee('これはテスト商品の説明です。');
        $response->assertSee('新品');
        $response->assertSee('item_images/test.jpg');
        $response->assertSee('1');
        $response->assertSee($this->user->name);
        $response->assertSee('これはテストコメントです。');
    }
}
