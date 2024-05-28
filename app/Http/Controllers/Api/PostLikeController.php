<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateCommentInput;
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

class PostLikeController
{

    public function index(Request $request, User $user, Profile $profile, Post $post): AnonymousResourceCollection
    {

        $profiles = QueryBuilder::for($post->likes(), $request)
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

    public function destroy(User $user, Profile $profile, Post $post): PostResource
    {
        $profile->postLikes()->detach();
        $post = $post->fresh();
        $post->load('likes');

        return new PostResource($post);
    }


    public function store(User $user, Profile $profile, Post $post): PostResource
    {
        $profile->postLikes()->attach($post);
        $post = $post->fresh();
        $post->load('likes');

        return new PostResource($post);
    }


}
