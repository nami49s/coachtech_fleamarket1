<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word, // ランダムなカテゴリ名
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
