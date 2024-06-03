<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateCommentInput;
use App\Actions\Post\CreateCommentAction;
use App\Actions\Post\DeleteCommentAction;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;


class CommentController
{

    public function index(Request $request, User $user, Profile $profile, Post $post): AnonymousResourceCollection
    {

        $comments = QueryBuilder::for(Comment::class, $request)
            ->allowedIncludes([
                'profile',
                'post',
                'likes',
                'likesCount',
            ])
            ->where('post_id', $post->id)
            ->defaultSort('-created_at')
            ->get();

        return CommentResource::collection($comments);
    }

    public function show(Request $request, User $user, Profile $profile, Post $post, Comment $comment): CommentResource
    {

        $comment = QueryBuilder::for(Comment::class, $request)
            ->allowedIncludes([
                'profile',
                'post',
                'likes',
                'likesCount',
            ])
            ->findOrFail($comment->id);

        return new CommentResource($comment);
    }

    /**
     * @throws Throwable
     */
    public function store(User $user, Profile $profile, Post $post, CreateCommentInput $input, CreateCommentAction $action): CommentResource
    {
        $post = $action->execute($user, $profile, $post, $input);

        return new CommentResource($post);
    }

    /**
     * @throws Throwable
     */
    public function destroy(User $user, Profile $profile, Post $post, Comment $comment, DeleteCommentAction $action): Response
    {

        $action->execute($user, $profile, $post, $comment);

        return response()->noContent();
    }



}
