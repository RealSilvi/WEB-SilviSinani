<?php

namespace App\Http\Controllers\Api;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Actions\Profile\CreateProfileAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Exceptions\CannotDeleteDefaultProfileException;
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
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class ProfileController
{
    /**
     * @throws ProfileIsNotAUserProfileException
     */
    public function index(Request $request, User $user): AnonymousResourceCollection
    {

        $profiles = QueryBuilder::for(Profile::class, $request)
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
            ->where('user_id', $user->id)
            ->get();

        if ($profiles->count() == 0) {
            throw new UserHasNotProfilesException('The user does not have profiles yet.');
        }

        return ProfileResource::collection($profiles);
    }


    /**
     * @throws Throwable
     */
    public function show(Request $request, User $user, int $profile): ProfileResource
    {

        $profile = QueryBuilder::for(Profile::class, $request)
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
            ->findOrFail($profile);

        if ($user->id != $profile->user_id) {
            throw new ProfileIsNotAUserProfileException('Profile does not match any of the user profiles.');
        }

        return new ProfileResource($profile);
    }

    /**
     * @throws ToManyProfilesException|Throwable
     */
    public function store(User $user, CreateProfileInput $input, CreateProfileAction $action): ProfileResource
    {
        if ($user->profiles()->count() > 3) {
            throw new ToManyProfilesException('Already 4 profiles existing for this user. Delete one of them before storing a new one');
        }

        $profile = $action->execute($user, $input);

        return new ProfileResource($profile);
    }

    /**
     * @throws Throwable
     * @throws NicknameAlreadyExistsException
     */
    public function update(User $user, Profile $profile, UpdateProfileInput $input, UpdateProfileAction $action): ProfileResource
    {
        if ($user->id != $profile->user_id) {
            throw new ProfileIsNotAUserProfileException('Profile does not match any of the user profiles.');
        }
        $updatedVariant = $action->execute($user, $profile, $input);

        return new ProfileResource($updatedVariant);
    }

    /**
     * @throws CannotDeleteDefaultProfileException
     * @throws ProfileIsNotAUserProfileException
     */
    public function destroy(User $user, Profile $profile, DeleteProfileAction $action): Response
    {
        if ($user->id != $profile->user_id) {
            throw new ProfileIsNotAUserProfileException('Profile does not match any of the user profiles.');
        }
        if ($user->profiles()->count() < 2) {
            throw new CannotDeleteDefaultProfileException('Cannot delete your last profile. If you want you can delete the user.');
        }
        $action->execute($user, $profile);

        return response()->noContent();
    }


}
