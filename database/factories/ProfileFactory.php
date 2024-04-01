<?php

namespace Database\Factories;

use App\Enum\ProfileType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nickname' => fake()->name,
            'bio' => fake()->text,
            'main_image' => fake()->url,
            'secondary_image' => fake()->url,
            'date_of_birth' => fake()->date,
            'default' => false,
            'type' => Arr::random(ProfileType::cases()) ,
            'breed' => fake()->name,
        ];
    }
}
