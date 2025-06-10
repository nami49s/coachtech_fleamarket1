<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\UserRating;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com'
        ]);

        Profile::where('user_id', $this->user->id)->delete();

        $this->user->profile()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile_images/profile.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'sample101'
        ]);
    }

    /** @test */
    public function ユーザー情報が正しく取得できる()
    {
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'buyer_id' => $this->user->id,
            'status' => 'purchased',
            'name' => 'テスト商品'
        ]);

        $this->actingAs($seller)
            ->post(route('ratings.store', ['item' => $item->id]), [
                'ratee_id' => $this->user->id,
                'rating' => 5,
            ]);

        $this->actingAs($this->user)
            ->post(route('ratings.store', ['item' => $item->id]), [
                'ratee_id' => $seller->id,
                'rating' => 5,
            ]);

        $item->refresh();
        $this->assertEquals('completed', $item->status);

        $this->actingAs($this->user);

        $response = $this->get('/mypage?tab=purchased');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee($item->name);
    }

    /** @test */
    public function 出品した商品が正しく表示される()
    {
        $item = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => '出品テスト商品'
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/mypage?tab=selling');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee($item->name);
    }

    /** @test */
    public function 取引中の商品が正しく表示される()
    {
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $this->user->id,
            'buyer_id' => $buyer->id,
            'status' => 'in_transaction',
            'name' => '取引中テスト商品'
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/mypage?tab=in_transaction');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee($item->name);
    }

    /** @test */
    public function プロフィールが存在しない場合でもエラーにならない()
    {
        $this->user->profile()->delete();

        $this->actingAs($this->user);

        $response = $this->get('/mypage');

        $response->assertStatus(200);
    }
}