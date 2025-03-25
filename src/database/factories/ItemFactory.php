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
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'item_image' => 'item_images/sample.jpg',
            'condition' => $this->faker->randomElement(['新品', '未使用', '目立った傷や汚れなし', 'やや傷や汚れあり', '傷や汚れあり']),
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(1000, 50000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'is_sold' => $this->faker->boolean(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
