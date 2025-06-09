<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザーは自分のチャットメッセージを編集できる()
    {
        $user = User::factory()->create();
        $message = ChatMessage::factory()->create([
            'user_id' => $user->id,
            'message' => '元のメッセージ',
        ]);

        $this->actingAs($user)
            ->put(route('chat.update', $message), [
                'message' => '編集されたメッセージ',
            ])
            ->assertRedirect(); // または assertStatus(200) など

        $this->assertDatabaseHas('chat_messages', [
            'id' => $message->id,
            'message' => '編集されたメッセージ',
        ]);
    }

    /** @test */
    public function ユーザーは自分のチャットメッセージを削除できる()
    {
        $user = User::factory()->create();
        $message = ChatMessage::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->delete(route('chat.destroy', $message))
            ->assertRedirect();

        $this->assertDatabaseMissing('chat_messages', [
            'id' => $message->id,
        ]);
    }

    /** @test */
    public function 他人のチャットメッセージは編集できない()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $message = ChatMessage::factory()->create([
            'user_id' => $other->id,
            'message' => '他人のメッセージ',
        ]);

        $this->actingAs($user)
            ->put(route('chat.update', $message), [
                'message' => '不正な編集',
            ])
            ->assertForbidden(); // ポリシーで403を返す想定
    }

    /** @test */
    public function 他人のチャットメッセージは削除できない()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $message = ChatMessage::factory()->create([
            'user_id' => $other->id,
        ]);

        $this->actingAs($user)
            ->delete(route('chat.destroy', $message))
            ->assertForbidden(); // ポリシーで403を返す想定
    }

    /** @test */
    public function 空の内容ではチャットメッセージを編集できない()
    {
        $user = User::factory()->create();
        $message = ChatMessage::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->put(route('chat.update', $message), [
                'message' => '',
            ])
            ->assertSessionHasErrors('message');

        // 内容が変わっていないことも確認
        $this->assertDatabaseHas('chat_messages', [
            'id' => $message->id,
            'message' => $message->message,
        ]);
    }
}