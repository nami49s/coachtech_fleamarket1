<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(), // ランダムなユーザー
            'item_image' => 'items/sample.jpg',
            'condition' => $this->faker->randomElement(['新品', '未使用', '目立った傷や汚れなし', 'やや傷や汚れあり', '傷や汚れあり']), // 商品の状態
            'name' => $this->faker->word(), // 商品名
            'brand' => $this->faker->company(), // ブランド名
            'description' => $this->faker->sentence(10), // 商品説明
            'price' => $this->faker->numberBetween(1000, 50000), // 価格（1000〜50000円）
            'status' => $this->faker->randomElement(['active', 'inactive']), // ステータス（仮でactive/inactive）
            'is_sold' => $this->faker->boolean(20), // 20%の確率で売り切れ
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
