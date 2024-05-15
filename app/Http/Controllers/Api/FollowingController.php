<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\ProfileFollowInput;
use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Actions\Profile\CreateProfileAction;
use App\Actions\Profile\CreateProfileFollowerAction;
use App\Actions\Profile\CreateProfileFollowingAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Actions\Profile\DeleteProfileFollowerAction;
use App\Actions\Profile\DeleteProfileFollowingAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Exceptions\CannotDeleteDefaultProfileException;
use App\Exceptions\CannotUnfollowYourselfException;
use App\Exceptions\FollowerNotFoundException;
use App\Exceptions\FollowingNotFoundException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Exceptions\ProfileIsNotAUserProfileException;
use App\Exceptions\ToManyProfilesException;
use App\Exceptions\UserHasNotProfilesException;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FollowingController
{
    /**
     * @throws Throwable
     */
    public function store(User $user, Profile $profile, ProfileFollowInput $input, CreateProfileFollowingAction $action): ProfileResource
    {
        $profile = $action->execute($profile, $input);

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


    public function index(User $user, Profile $profile): AnonymousResourceCollection
    {
        return ProfileResource::collection($profile->following()->get());
    }

}
