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

        Item::factory(10)->create([
            'category_id' => $categories->random()->id // 既存のカテゴリをランダムに使用
        ]);
    }
}
