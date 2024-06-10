<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateCommentInput;
use App\Actions\Data\CreateNewsInput;
use App\Actions\Profile\CreateNewsAction;
use App\Enum\NewsType;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CommentLikeController
{

    public function index(Request $request, User $user, Profile $profile, Post $post, Comment $comment): AnonymousResourceCollection
    {

        $profiles = QueryBuilder::for($comment->likes(), $request)
            ->allowedIncludes([
                'user',
                'news',
                'allNews',
                'receivedRequests',
                'sentRequests',
                'followers',
                'following',
                'pendingFollowers',
                'comments',
                'postLikes',
                'commentLikes',
                'lastPost',
                'posts',
            ])
            ->get();

        return ProfileResource::collection($profiles);
    }

    public function destroy(User $user, Profile $profile, Post $post, Comment $comment): CommentResource
    {
        $profile->commentLikes()->detach();
        $comment = $comment->fresh();
        $comment->load('likes');

        return new CommentResource($comment);
    }


    /**
     * @throws \Throwable
     */
    public function store(User $user, Profile $profile, Post $post, Comment $comment): CommentResource
    {
        $profile->commentLikes()->attach($comment);
        $comment = $comment->fresh();
        $comment->load('likes');

        app(CreateNewsAction::class)->execute($user, $profile, new CreateNewsInput(
            fromId: $comment->id,
            fromType: Comment::class,
            profileId: $comment->profile_id,
            type: NewsType::COMMENT_LIKE,
            title: $profile->nickname . ' likes your comment.',
        ));

        return new CommentResource($comment);
    }


}
