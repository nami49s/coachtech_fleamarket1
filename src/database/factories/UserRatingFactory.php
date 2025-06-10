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

            'ratee_id' => User::factory(),

            'item_id' => Item::factory(),

            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
