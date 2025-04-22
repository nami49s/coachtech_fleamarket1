<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Profile::where('user_id', $this->user->id)->delete();

        $this->user->profile()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'storage/profile_images/profile.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'sample101'
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function ユーザー情報が正しく取得できる()
    {
        $item1 = Item::factory()->create(['user_id' => $this->user->id]);
        $item2 = Item::factory()->create(['user_id' => $this->user->id]);

        $item3 = Item::factory()->create();
        $item4 = Item::factory()->create();

        $this->user->purchases()->createMany([
            ['item_id' => $item3->id, 'payment_method' => 'credit_card'],
            ['item_id' => $item4->id, 'payment_method' => 'credit_card']
        ]);

        $response = $this->get(route('mypage'));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');

        $response->assertSee($item1->name);
        $response->assertSee($item2->name);
        $response->assertSee($item3->name);
        $response->assertSee($item4->name);
    }
}
