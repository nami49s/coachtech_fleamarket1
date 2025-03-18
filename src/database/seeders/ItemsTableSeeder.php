<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

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

        if ($categories->isEmpty()) {
            $this->command->warn('カテゴリが存在しません。CategoriesTableSeeder を先に実行してください。');
            return;
        }

        Item::factory(10)->create()->each(function ($item) use ($categories) {
            // 各アイテムにランダムなカテゴリーを1〜3つ関連付ける
            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
