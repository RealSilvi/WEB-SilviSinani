<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\ProfileFollowInput;
use App\Actions\Profile\CreateProfileFollowerAction;
use App\Actions\Profile\DeleteProfileFollowerAction;
use App\Exceptions\CannotUnfollowYourselfException;
use App\Exceptions\FollowerNotFoundException;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FollowersController
{
    public function index(Request $request, User $user, Profile $profile): AnonymousResourceCollection
    {
        $profiles = QueryBuilder::for($profile->followers(), $request)
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
            ->simplePaginate(9);

        return ProfileResource::collection($profiles);
    }

    /**
     * @throws Throwable
     */
    public function store(User $user, Profile $profile, ProfileFollowInput $input, CreateProfileFollowerAction $action): ProfileResource
    {
        $profile = $action->execute($profile, $input);

        return new ProfileResource($profile);
    }

    /**
     * @throws CannotUnfollowYourselfException
     * @throws FollowerNotFoundException
     */
    public function destroy(User $user, Profile $profile, Profile $follower, DeleteProfileFollowerAction $action): ProfileResource
    {
        $profile = $action->execute($profile, $follower);

        return new ProfileResource($profile);
    }
}
