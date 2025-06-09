<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\UserRating;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompletedMail;

class UserRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログインユーザーは正しい入力で評価を送信できる()
    {
        Mail::fake();

        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        // 購入者が評価者、出品者が評価先になるようにセット
        $item = Item::factory()->create([
            'user_id' => $seller->id,  // 出品者
            'buyer_id' => $buyer->id,  // 購入者
            'status' => 'in_transaction',
        ]);

        $response = $this->actingAs($buyer)->post(route('ratings.store', $item), [
            'ratee_id' => $seller->id,
            'rating' => 4,
        ]);

        $response->assertRedirect(route('top'));
        $response->assertSessionHas('success', '評価を送信しました');

        $this->assertDatabaseHas('user_ratings', [
            'rater_id' => $buyer->id,
            'ratee_id' => $seller->id,
            'item_id' => $item->id,
            'rating' => 4,
        ]);

        // 購入者が出品者を評価したのでメール送信あり
        Mail::assertSent(TransactionCompletedMail::class);
    }

    /** @test */
    public function ２回目の評価は二重評価として拒否される()
    {
        $rater = User::factory()->create();
        $ratee = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $ratee->id,
            'buyer_id' => $rater->id,
        ]);

        // 1回目の評価を作成
        UserRating::factory()->create([
            'rater_id' => $rater->id,
            'ratee_id' => $ratee->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($rater)->post(route('ratings.store', $item), [
            'ratee_id' => $ratee->id,
            'rating' => 3,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'すでに評価済みです');
    }

    /** @test */
    public function 評価値が不正だとバリデーションエラーになる()
    {
        $rater = User::factory()->create();
        $ratee = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($rater)->post(route('ratings.store', $item), [
            'ratee_id' => $ratee->id,
            'rating' => 6, // 1~5以外
        ]);

        $response->assertSessionHasErrors('rating');
    }

    /** @test */
    public function ２件目の評価送信後に商品ステータスがcompletedに変更される()
    {
        Mail::fake();

        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,  // 出品者
            'buyer_id' => $buyer->id,  // 購入者
            'status' => 'in_transaction',
        ]);

        // 1人目の評価：購入者が出品者を評価（メール送信される）
        UserRating::factory()->create([
            'rater_id' => $buyer->id,
            'ratee_id' => $seller->id,
            'item_id' => $item->id,
        ]);

        // 2人目の評価：出品者が購入者を評価（メール送信はなし）
        $response = $this->actingAs($seller)->post(route('ratings.store', $item), [
            'ratee_id' => $buyer->id,
            'rating' => 5,
        ]);

        $response->assertRedirect(route('top'));
        $response->assertSessionHas('success', '評価を送信しました');

        $item->refresh();
        $this->assertEquals('completed', $item->status);
    }
}