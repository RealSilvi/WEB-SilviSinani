<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeletePostAction
{
    public function execute(User $user, Profile $profile, Post $post): void
    {
        DB::transaction(function () use ($profile, $post): void {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->profile()->dissociate();

            $post->comments()->each(function (Comment $comment) {
                $comment->likes()->each(fn (Profile $profile) => $profile->commentLikes()->detach($comment));
                $comment->delete();
            });

            $post->likes()->each(fn (Profile $profile) => $profile->postLikes()->detach($post));
            $post->delete();
        });
    }
}
