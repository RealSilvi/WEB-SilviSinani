<?php

namespace Database\Seeders;

use App\Enum\ProfileType;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Ramsey\Collection\Collection;

class LikesSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = Profile::query()->get();
        foreach (Post::all() as $post) {
            for ($i = 0; $i < rand(0, 20); $i++) {
                $profile = $profiles->random();
                if (!$post->likes()->find($profile->id)) {
                    $post->likes()->attach($profile->id);
                }
            }
            $post->save();
        }
        foreach (Comment::all() as $comment) {
            for ($i = 0; $i < rand(0, 20); $i++) {
                $profile = $profiles->random();
                if (!$comment->likes()->find($profile->id)) {
                    $comment->likes()->attach($profile->id);
                }
            }
            $comment->save();
        }
    }
}
