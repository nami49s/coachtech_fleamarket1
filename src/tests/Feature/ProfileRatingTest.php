<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserRating;

class ProfileRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 評価がある場合はプロフィールに平均評価が四捨五入されて表示される()
    {
        // テストユーザー作成
        $user = User::factory()->create();

        // 取引評価を複数作成（例: 3件 3,4,5の評価）
        UserRating::factory()->create(['ratee_id' => $user->id, 'rating' => 3]);
        UserRating::factory()->create(['ratee_id' => $user->id, 'rating' => 4]);
        UserRating::factory()->create(['ratee_id' => $user->id, 'rating' => 5]);

        // プロフィール画面にアクセス
        $response = $this->actingAs($user)->get(route('mypage', $user));

        // 評価平均 = (3 + 4 + 5) / 3 = 4.0
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '<span class="star filled">&#9733;</span>',
            '<span class="star filled">&#9733;</span>',
            '<span class="star filled">&#9733;</span>',
            '<span class="star filled">&#9733;</span>',
            '<span class="star">&#9733;</span>',
        ], false);
    }

    /** @test */
    public function 評価がない場合は評価平均は表示されない()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('mypage', $user));

        $response->assertStatus(200);
        $response->assertDontSee('評価平均'); // 評価平均の表示なしを確認
    }

    /** @test */
    public function 評価の平均値が小数の場合は四捨五入されて表示される()
    {
        $user = User::factory()->create();

        // 例: 評価が 3,4 の場合 平均3.5→四捨五入で4
        UserRating::factory()->create(['ratee_id' => $user->id, 'rating' => 3]);
        UserRating::factory()->create(['ratee_id' => $user->id, 'rating' => 4]);

        $response = $this->actingAs($user)->get(route('mypage', $user));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '<span class="star filled">&#9733;</span>',
            '<span class="star filled">&#9733;</span>',
            '<span class="star filled">&#9733;</span>',
            '<span class="star filled">&#9733;</span>',
            '<span class="star">&#9733;</span>',
        ], false);
    }
}
