<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Data\CreateCommentInput;
use App\Actions\Data\CreateNewsInput;
use App\Actions\Data\CreatePostInput;
use App\Actions\Data\ProfileFollowInput;
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

        $this->sendNews($user, $profile, $post);

        return $comment;
    }

    /**
     * @throws Throwable
     */
    public function sendNews(User $user, Profile $profile, Post $post): void
    {
        if ($profile->id !== $post->profile_id) {
            app(CreateNewsAction::class)->execute($user, $profile, new CreateNewsInput(
                fromId: $post->id,
                fromType: Post::class,
                profileId: $post->profile_id,
                type: NewsType::COMMENT,
                fromNickname: $profile->nickname,
            ));
        }
    }
}
