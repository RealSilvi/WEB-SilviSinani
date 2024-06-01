<?php

namespace Database\Factories;

use App\Http\Resources\PostResource;
use App\Models\Comment;
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
            'body' => fake()->sentence,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Comment $comment) {
            if ($comment->profile_id == null) {
                $comment->profile_id = Profile::factory()->create()->id;
            }
            if ($comment->post_id == null) {
                $comment->post_id = Post::factory()->create()->id;
            }
        });
    }
}
