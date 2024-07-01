<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::query()->get();
        foreach (Post::all() as $post) {
            for ($i = 0; $i < rand(0, 5); $i++) {
                $profile = $profiles->random();
                Comment::factory()->for($profile)->for($post)->create();
            }
        }
    }
}
