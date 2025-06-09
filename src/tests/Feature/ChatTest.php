<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Item;
use App\Models\ChatMessage;
use App\Services\ImageServiceInterface;
use Tests\Fakes\FakeImageService;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // ImageServiceInterface をFakeImageService にバインド
        $this->app->bind(ImageServiceInterface::class, FakeImageService::class);

        // ストレージもフェイク
        Storage::fake('public');
    }

    /** @test */
    public function 正しい入力内容でチャット投稿できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id // ユーザーがアクセス権を持つように設定
        ]);

        // GD拡張なしでも動作するように、通常のファイルを作成
        $fakeImage = UploadedFile::fake()->create('chat.jpeg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('chat.store', $item), [
            'message' => 'これは有効なチャットメッセージです。', // 'body' → 'message'
            'image' => $fakeImage,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chat_messages', [ // 'chats' → 'chat_messages'
            'message' => 'これは有効なチャットメッセージです。',
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function 本文が未入力の場合はバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id
        ]);

        // GD拡張なしでも動作するように、通常のファイルを作成
        $fakeImage = UploadedFile::fake()->create('chat.jpeg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('chat.store', $item), [
            'message' => '', // 'body' → 'message'
            'image' => $fakeImage,
        ]);

        $response->assertSessionHasErrors(['message']); // 'body' → 'message'
        $this->assertEquals('本文を入力してください', session('errors')->first('message'));
    }

    /** @test */
    public function 本文が401文字以上の場合はバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id
        ]);

        // GD拡張なしでも動作するように、通常のファイルを作成
        $fakeImage = UploadedFile::fake()->create('chat.jpeg', 100, 'image/jpeg');

        $tooLong = str_repeat('あ', 401);
        $response = $this->actingAs($user)->post(route('chat.store', $item), [
            'message' => $tooLong, // 'body' → 'message'
            'image' => $fakeImage,
        ]);

        $response->assertSessionHasErrors(['message']); // 'body' → 'message'
        $this->assertEquals('本文は400文字以内で入力してください', session('errors')->first('message'));
    }

    /** @test */
    public function 画像がjpegやpng以外の場合はバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id
        ]);

        $invalidFile = UploadedFile::fake()->create('invalid.exe', 100);
        $response = $this->actingAs($user)->post(route('chat.store', $item), [
            'message' => 'テスト本文', // 'body' → 'message'
            'image' => $invalidFile,
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertEquals('「.png」または「.jpeg」形式でアップロードしてください', session('errors')->first('image'));
    }

    /** @test */
    public function 入力途中の本文がセッションに保持されていればフォームに表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id
        ]);

        $message = '入力保持テストの本文です。';

        $response = $this->actingAs($user)->withSession([
            '_old_input' => [
                'message' => $message, // 'body' → 'message'
            ],
        ])->get(route('chat.show', $item));

        $response->assertSee($message);
    }

    /** @test */
    public function アクセス権のないアイテムにはチャット投稿できない()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'buyer_id' => null // このユーザーは購入者でもない
        ]);

        $response = $this->actingAs($user)->post(route('chat.store', $item), [
            'message' => 'テストメッセージ',
        ]);

        $response->assertStatus(403);
    }
}