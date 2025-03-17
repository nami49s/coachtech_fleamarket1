<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test' . time() . '@example.com',
            'password' => Hash::make('password'),
        ]);

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'profile_images/profile.jpg',
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101',
        ]);

        User::factory(10)->create()->each(function ($user) {
            Profile::factory()->create(['user_id' => $user->id]);
        });
    }
}
