<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Profile::all() as $profile) {
            Post::factory()->for($profile)->count(rand(1, 10))->create();
        }

    }
}
