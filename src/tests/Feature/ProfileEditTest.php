<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール編集ページで過去に設定した情報が初期値として表示される()
    {
        $user = User::factory()->create();
        Profile::where('user_id', $user->id)->delete();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'profile_image' => 'storage/profile_images/profile.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'サンプルビル101'
        ]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // プロフィール編集ページにアクセス
        $response = $this->get(route('mypage.profile'));

        // 各項目が正しく表示されていることを確認
        $response->assertSee('テストユーザー');
        $response->assertSee('storage/profile_images/profile.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区1-1-1');
        $response->assertSee('サンプルビル101');

        // ステータスコード 200 を確認
        $response->assertStatus(200);
    }
}
