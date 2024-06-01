<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
           AdminSeeder::class,
           ProfilesSeeder::class,
           FollowersSeeder::class,
           PostsSeeder::class,
           CommentsSeeder::class,
           LikesSeeder::class,
        ]);
    }
}
