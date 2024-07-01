<?php

namespace Database\Factories;

use App\Enum\NewsType;
use App\Models\Comment;
use App\Models\News;
use App\Models\Post;
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
            'from_nickname' => Profile::query()->inRandomOrder()->first()?->nickname ?? $this->faker->userName(),
            'seen' => false,
            'type' => Arr::random(NewsType::cases()),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (News $news) {
            if ($news->profile_id == null) {
                $news->profile_id = Profile::factory()->create()->id;
            }
            if ($news->from == null) {

                $from = match ($news->type) {
                    NewsType::POST_LIKE => Post::query()->inRandomOrder()->first() ?? Post::factory()->create(),
                    NewsType::COMMENT_LIKE, NewsType::COMMENT => Comment::query()->inRandomOrder()->first() ?? Comment::factory()->create(),
                    NewsType::FOLLOW_REQUEST => Profile::query()->where('id', '!=', $news->profile_id)->inRandomOrder()->first() ?? Profile::factory()->create(),
                };

                $news->from_id = $from->id;
                $news->from_type = get_class($from);
            }
        });
    }
}
