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

        $fixedUsers = [
            [
                'name' => 'ユーザーA',
                'email' => 'usera@example.com',
                'password' => Hash::make('password'),
                'profile' => [
                    'profile_image' => 'profile_images/profile.jpg',
                    'name' => 'ユーザーA',
                    'postal_code' => '111-1111',
                    'address' => 'A県A市',
                    'building' => 'Aビル101',
                ]
            ],
            [
                'name' => 'ユーザーB',
                'email' => 'userb@example.com',
                'password' => Hash::make('password'),
                'profile' => [
                    'profile_image' => 'profile_images/profile.jpg',
                    'name' => 'ユーザーB',
                    'postal_code' => '222-2222',
                    'address' => 'B県B市',
                    'building' => 'Bビル202',
                ]
            ],
            [
                'name' => 'ユーザーC',
                'email' => 'userc@example.com',
                'password' => Hash::make('password'),
                'profile' => [
                    'profile_image' => 'profile_images/profile.jpg',
                    'name' => 'ユーザーC',
                    'postal_code' => '333-3333',
                    'address' => 'C県C市',
                    'building' => 'Cビル303',
                ]
            ]
        ];

        foreach ($fixedUsers as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            Profile::create(array_merge(
                ['user_id' => $user->id],
                $data['profile']
            ));
        }

        User::factory(7)->create()->each(function ($user) {
            Profile::factory()->create(['user_id' => $user->id]);
        });
    }
}
