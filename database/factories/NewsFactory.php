<?php

namespace Database\Factories;

use App\Enum\NewsType;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title,
            'body' => fake()->realText,
            'seen' => false,
            'profile_id' => Profile::query()->inRandomOrder()->first()?->id ?? Profile::factory()->create()->id,
            'from' => Profile::factory()->create()->id,
            'type' => Arr::random(NewsType::cases()),
        ];
    }
}
