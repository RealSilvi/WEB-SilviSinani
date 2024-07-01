<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\ProfileFollowInput;
use App\Actions\Profile\CreateProfileFollowingAction;
use App\Actions\Profile\DeleteProfileFollowingAction;
use App\Exceptions\CannotUnfollowYourselfException;
use App\Exceptions\FollowingNotFoundException;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FollowingController
{
    public function index(Request $request, User $user, Profile $profile): AnonymousResourceCollection
    {
        $profiles = QueryBuilder::for($profile->following(), $request)
            ->allowedIncludes([
                'user',
                'news',
                'allNews',
                'receivedRequests',
                'sentRequests',
                'followers',
                'following',
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
    public function store(User $user, Profile $profile, ProfileFollowInput $input, CreateProfileFollowingAction $action): ProfileResource
    {
        $profile = $action->execute($user, $profile, $input);

        return new ProfileResource($profile);
    }

    /**
     * @throws FollowingNotFoundException
     * @throws CannotUnfollowYourselfException
     */
    public function destroy(User $user, Profile $profile, Profile $following, DeleteProfileFollowingAction $action): ProfileResource
    {
        $profile = $action->execute($profile, $following);

        return new ProfileResource($profile);
    }
}
