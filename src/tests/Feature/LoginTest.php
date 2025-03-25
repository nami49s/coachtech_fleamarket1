<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function メールアドレスが空の場合はエラーメッセージが出る()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが空の場合はエラーメッセージが出る()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 入力情報が間違っている場合エラーメッセージが出る()
    {
        $response = $this->post('/login', [
            'email' => 'notexist@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function 正しい情報を入力するとログイン処理が実行される()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), 
        ]);


        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);


        $this->assertAuthenticatedAs($user);
    }
}