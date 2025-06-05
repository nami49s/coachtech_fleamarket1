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
        // 出品者をランダムに取得
        $seller = User::inRandomOrder()->first();

        // 出品者とは別のユーザーを購入者として取得（ただし存在する場合）
        $buyer = User::where('id', '!=', optional($seller)->id)->inRandomOrder()->first();

        // 状態をランダムに設定
        $status = $this->faker->randomElement([
            Item::STATUS_ACTIVE,
            Item::STATUS_IN_TRANSACTION,
            Item::STATUS_COMPLETED,
        ]);

        return [
            'user_id' => $seller->id ?? User::factory(), // 出品者
            'buyer_id' => in_array($status, [Item::STATUS_IN_TRANSACTION, Item::STATUS_COMPLETED])
                ? optional($buyer)->id
                : null,
            'item_image' => 'item_images/sample.jpg',
            'condition' => $this->faker->randomElement(['新品', '未使用', '目立った傷や汚れなし', 'やや傷や汚れあり', '傷や汚れあり']),
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(1000, 50000),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
