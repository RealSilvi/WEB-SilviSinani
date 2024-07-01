<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreatePostInput;
use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class PostController
{
    public function index(Request $request, User $user, Profile $profile): AnonymousResourceCollection
    {

        $posts = QueryBuilder::for(Post::class, $request)
            ->allowedIncludes([
                'profile',
                'likes',
                'comments',
                'comments.profile',
                'comments.likes',
            ])
            ->defaultSort('-created_at')
            ->where('profile_id', $profile->id)
            ->simplePaginate(10);

        return PostResource::collection($posts);
    }

    public function show(Request $request, User $user, Profile $profile, Post $post): PostResource
    {

        $profile = QueryBuilder::for(Post::class, $request)
            ->allowedIncludes([
                'profile',
                'comments',
                'comments.profile',
                'comments.likes',
                'comments.likesCount',
                'likes',
                'likesCount',
                'commentsCount',
            ])
            ->findOrFail($post->id);

        return new PostResource($profile);
    }

    /**
     * @throws Throwable
     */
    public function store(User $user, Profile $profile, CreatePostInput $input, CreatePostAction $action): PostResource
    {

        $post = $action->execute($user, $profile, $input);

        $post->load('profile');

        return new PostResource($post);
    }

    public function destroy(User $user, Profile $profile, Post $post, DeletePostAction $action): Response
    {

        $action->execute($user, $profile, $post);

        return response()->noContent();
    }
}
