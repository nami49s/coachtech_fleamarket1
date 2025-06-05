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

        $userA = User::where('email', 'usera@example.com')->first();
        $userB = User::where('email', 'userb@example.com')->first();

        if (!$userA || !$userB) {
            $this->command->warn('ユーザーAまたはBが見つかりません。UsersTableSeeder を確認してください。');
            return;
        }

        $fixedItems = [
            [
                'name' => '腕時計',
                'brand' => '',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => '15000',
                'condition' => '良好',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/ArmaniMensClock.jpg'
            ],
            [
                'name' => 'HDD',
                'brand' => '',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => '5000',
                'condition' => '目立った傷や汚れなし',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/HDDHardDisk.jpg'
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => '',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => '300',
                'condition' => 'やや傷や汚れあり',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/iLoveIMGd.jpg'
            ],
            [
                'name' => '革靴',
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'price' => '4000',
                'condition' => '状態が悪い',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/LeatherShoesProductPhoto.jpg'
            ],
            [
                'name' => 'ノートPC',
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'price' => '45000',
                'condition' => '良好',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/LivingRoomLaptop.jpg'
            ],
            [
                'name' => 'マイク',
                'brand' => '',
                'description' => '高音質のレコーディング用マイク',
                'price' => '8000',
                'condition' => '目立った傷や汚れなし',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/MusicMic4632231.jpg'
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => '3500',
                'condition' => 'やや傷や汚れあり',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/Pursefashionpocket.jpg'
            ],
            [
                'name' => 'タンブラー',
                'brand' => '',
                'description' => '使いやすいタンブラー',
                'price' => '500',
                'condition' => '状態が悪い',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/Tumblersouvenir.jpg'
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => '',
                'description' => '手動のコーヒーミル',
                'price' => '4000',
                'condition' => '良好',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/WaitresswithCoffeeGrinder.jpg'
            ],
            [
                'name' => 'メイクセット',
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'price' => '2500',
                'condition' => '目立った傷や汚れなし',
                'status' => Item::STATUS_ACTIVE,
                'item_image' => 'item_images/makeset.jpg'
            ],
        ];

        foreach ($fixedItems as $index =>$data) {
            $user = $index < 5 ? $userA : $userB;

            $item = Item::create([
                'user_id' => $user->id,
                'item_image' => $data['item_image'],
                'condition' => $data['condition'],
                'name' => $data['name'],
                'brand' => $data['brand'],
                'description' => $data['description'],
                'price' => $data['price'],
                'status' => $data['status'],
            ]);

            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }

        Item::factory(10)->create()->each(function ($item) use ($categories, $users) {
            // 出品者をランダムに再設定（念のため）
            $seller = $users->random();
            $item->update([
                'user_id' => $seller->id,
                'item_image' => 'item_images/sample.jpg',
            ]);

            // ステータスに応じて購入者を設定
            if (in_array($item->status, [Item::STATUS_IN_TRANSACTION, Item::STATUS_COMPLETED])) {
                // 出品者以外のユーザーから購入者を選ぶ
                $buyer = $users->where('id', '!=', $seller->id)->random();
                $item->update([
                    'buyer_id' => $buyer->id,
                ]);
            }

            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
