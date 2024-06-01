<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Profile;
use App\Support\ImageGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => null,
            'description' => fake()->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Post $post) {
            if($post->profile_id == null){
                $post->profile_id=Profile::factory()->create()->id;
            }
        });
    }

}
