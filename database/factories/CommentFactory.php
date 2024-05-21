<?php

namespace Database\Factories;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => fake()->realText,
            'post_id' => Post::factory()->create()->id,
            'profile_id' => Profile::factory()->create()->id,
        ];
    }
}
