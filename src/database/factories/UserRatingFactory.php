<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserRating;
use App\Models\User;
use App\Models\Item;


class UserRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = UserRating::class;
    
    public function definition()
    {
        return [
            'rater_id' => User::factory(),

            // 評価される側のユーザーID（ratee_id）
            'ratee_id' => User::factory(),

            // 評価対象の商品ID（item_id）
            'item_id' => Item::factory(),

            // 評価値は1〜5の整数でランダムに生成
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
