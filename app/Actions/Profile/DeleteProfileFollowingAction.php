<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Exceptions\CannotUnfollowYourselfException;
use App\Exceptions\FollowingNotFoundException;
use App\Models\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteProfileFollowingAction
{
    /**
     * @throws CannotUnfollowYourselfException
     * @throws FollowingNotFoundException
     */
    public function execute(Profile $profile, Profile $follower): Profile
    {
        $this->validateInput($profile, $follower);

        return $this->stopFollowing($profile, $follower);
    }

    public function stopFollowing(Profile $profile, Profile $follower): Profile
    {
        $profile->sentRequests()->detach($follower->id);

        return $profile->load(['sentRequests', 'following']);
    }

    /**
     * @throws CannotUnfollowYourselfException
     * @throws FollowingNotFoundException
     */
    protected function validateInput(Profile $profile, Profile $follower): void
    {
        if ($profile->id == $follower->id) {
            throw new CannotUnfollowYourselfException('You cannot unfollow yourself');
        }

        if (! Profile::query()->find($follower->id)->exists()) {
            throw new ModelNotFoundException('Profile with id:'.$follower->id.' does not exist');
        }

        if (! $profile->sentRequests()->find($follower->id)) {
            throw new FollowingNotFoundException('Following not found');
        }
    }
}
