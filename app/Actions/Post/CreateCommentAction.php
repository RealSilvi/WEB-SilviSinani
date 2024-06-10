<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Data\CreateCommentInput;
use App\Actions\Data\CreateNewsInput;
use App\Actions\Data\CreatePostInput;
use App\Actions\Profile\CreateNewsAction;
use App\Enum\NewsType;
use App\Exceptions\CannotCreateAnEmptyPostException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use Throwable;

class CreateCommentAction
{
    /**
     * @throws Throwable
     */
    public function execute(User $user, Profile $profile, Post $post, CreateCommentInput $input): Comment
    {

        return DB::transaction(function () use ($user, $profile, $post, $input): Comment {
            return $this->createComment($user, $profile, $post, $input);
        });
    }


    /**
     * Validate and create a newly registered profile.
     *
     * @param User $user
     * @param Profile $profile
     * @param Post $post
     * @param CreateCommentInput $input
     * @return Comment
     * @throws Throwable
     */
    public function createComment(User $user, Profile $profile, Post $post, CreateCommentInput $input): Comment
    {
        $comment = new Comment([
            'body' => $input->body,
            'profile_id' => $profile->id,
            'post_id' => $post->id,
        ]);

        $comment->save();

        app(CreateNewsAction::class)->execute($user, $profile, new CreateNewsInput(
            fromId: $comment->id,
            fromType: Comment::class,
            profileId: $post->profile_id,
            type: NewsType::COMMENT,
            title: $profile->nickname . ' commented your post.',
        ));

        return $comment;
    }
}
