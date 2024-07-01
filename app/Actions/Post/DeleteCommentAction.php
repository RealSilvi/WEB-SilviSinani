<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Exceptions\CannotDeleteDefaultOthersCommentsException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeleteCommentAction
{
    /**
     * @throws Throwable
     */
    public function execute(User $user, Profile $profile, Post $post, Comment $comment): void
    {
        DB::transaction(function () use ($profile, $post, $comment): void {

            if ($comment->profile_id != $profile->id && $post->profile_id != $profile->id) {
                throw new CannotDeleteDefaultOthersCommentsException('Cannot delete the comment if the comment or the post is not your own.');
            }

            $comment->post()->dissociate();
            $comment->profile()->dissociate();
            $comment->likes()->each(fn (Profile $profile) => $profile->commentLikes()->detach($post));
            $comment->delete();
        });
    }
}
