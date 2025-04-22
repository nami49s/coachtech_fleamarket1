<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ItemCreateTest extends TestCase
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
    public function 商品出品画面で必要な情報が正しく保存される()
    {
        $category = Category::factory()->create();

        $itemData = [
            'name' => 'テスト商品',
            'item_image' => UploadedFile::fake()->create('test_image.jpg', 100),
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品です',
            'price' => '1000',
            'category_ids' => [$category->id],
            'condition' => '良好',
            'user_id' => $this->user->id,
        ];

        $response = $this->post(route('sell.store'), $itemData);

        $item = Item::where('name', 'テスト商品')->first();
        $this->assertNotNull($item, "商品がデータベースに保存されていません");

        $this->assertDatabaseHas('item_category', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('mypage'));
    }
}
