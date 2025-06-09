<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = ChatMessage::class;

    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'message' => $this->faker->sentence(),
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
