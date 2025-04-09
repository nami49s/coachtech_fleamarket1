<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        $users = User::all();

        if ($categories->isEmpty()) {
            $this->command->warn('カテゴリが存在しません。CategoriesTableSeeder を先に実行してください。');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('ユーザーが存在しません。UsersTableSeeder を先に実行してください。');
            return;
        }

        $fixedItems = [
            [
                'name' => '腕時計',
                'brand' => '',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => '15000',
                'condition' => '良好',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'items/Armani+Mens+Clock.jpg'
            ],
            [
                'name' => 'HDD',
                'brand' => '',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => '5000',
                'condition' => '目立った傷や汚れなし',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'items/HDD+Hard+Disk.jpg'
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => '',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => '300',
                'condition' => 'やや傷や汚れあり',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'items/iLoveIMG+d.jpg'
            ],
            [
                'name' => '革靴',
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'price' => '4000',
                'condition' => '状態が悪い',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/Leather+Shoes+Product+Photo.jpg'
            ],
            [
                'name' => 'ノートPC',
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'price' => '45000',
                'condition' => '良好',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/Living+Room+Laptop.jpg'
            ],
            [
                'name' => 'マイク',
                'brand' => '',
                'description' => '高音質のレコーディング用マイク',
                'price' => '8000',
                'condition' => '目立った傷や汚れなし',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/Music+Mic+4632231.jpg'
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => '3500',
                'condition' => 'やや傷や汚れあり',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/Purse+fashion+pocket.jpg'
            ],
            [
                'name' => 'タンブラー',
                'brand' => '',
                'description' => '使いやすいタンブラー',
                'price' => '500',
                'condition' => '状態が悪い',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/Tumbler+souvenir.jpg'
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => '',
                'description' => '手動のコーヒーミル',
                'price' => '4000',
                'condition' => '良好',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/Waitress+with+Coffee+Grinder.jpg'
            ],
            [
                'name' => 'メイクセット',
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'price' => '2500',
                'condition' => '目立った傷や汚れなし',
                'status' => 'available',
                'is_sold' => '0',
                'item_image' => 'item_images/makeset.jpg'
            ],
        ];

        foreach ($fixedItems as $data) {
            $item = Item::create([
                'user_id' => $users->random()->id,
                'item_image' => $data['item_image'],
                'condition' => $data['condition'],
                'name' => $data['name'],
                'brand' => $data['brand'],
                'description' => $data['description'],
                'price' => $data['price'],
                'status' => $data['status'],
                'is_sold' => $data['is_sold'],
            ]);

            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

        Item::factory(10)->create()->each(function ($item) use ($categories, $users) {

            $item->update([
                'user_id' => $users->random()->id,
                'item_image' => 'item_images/sample.jpg',
            ]);

            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
