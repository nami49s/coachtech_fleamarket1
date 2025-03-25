<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;
use App\Models\User;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'profile_image' => 'profile_images/profile.jpg',
            'name' => $this->faker->name(),
            'postal_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building' => $this->faker->optional()->secondaryAddress(),
        ];
    }
}
