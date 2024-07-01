<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateNewsInput;
use App\Actions\Profile\CreateNewsAction;
use App\Enum\NewsType;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

    /**
     * @throws \Throwable
     */
    public function store(User $user, Profile $profile, Post $post): PostResource
    {
        $profile->postLikes()->attach($post);
        $post = $post->fresh();
        $post->load('likes');

        if ($profile->id !== $post->profile_id) {
            app(CreateNewsAction::class)->execute($user, $profile, new CreateNewsInput(
                fromId: $post->id,
                fromType: Post::class,
                profileId: $post->profile_id,
                type: NewsType::POST_LIKE,
                fromNickname: $profile->nickname,
            ));
        }

        return new PostResource($post);
    }
}
