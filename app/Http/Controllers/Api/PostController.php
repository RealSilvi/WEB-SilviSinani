<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreatePostInput;
use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Profile\CreateProfileAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Exceptions\CannotDeleteDefaultProfileException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Exceptions\ProfileIsNotAUserProfileException;
use App\Exceptions\ToManyProfilesException;
use App\Exceptions\UserHasNotProfilesException;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
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
                'comments',
                'likes',
            ])
            ->defaultSort('-created_at')
            ->where('profile_id', $profile->id)
            ->get();

        return PostResource::collection($posts);
    }

    public function show(Request $request, User $user, Profile $profile, Post $post): PostResource
    {

        $profile = QueryBuilder::for(Post::class, $request)
            ->allowedIncludes([
                'profile',
                'comments',
                'likes',
            ])
            ->findOrFail($post->id);

        return new PostResource($profile);
    }

    public function destroy(User $user, Profile $profile, Post $post, DeletePostAction $action): Response
    {

        $action->execute($user, $profile, $post);

        return response()->noContent();
    }


    public function store(User $user, Profile $profile, CreatePostInput $input, CreatePostAction $action): PostResource
    {

        $post = $action->execute($user,$profile, $input);

        return new PostResource($post);
    }


}
