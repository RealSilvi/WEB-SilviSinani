<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Exceptions\CannotUnfollowYourselfException;
use App\Exceptions\FollowerNotFoundException;
use App\Models\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteProfileFollowerAction
{
    /**
     * @throws CannotUnfollowYourselfException
     * @throws FollowerNotFoundException
     */
    public function execute(Profile $profile, Profile $follower): Profile
    {
        $this->validateInput($profile, $follower);

        return $this->deleteFollower($profile, $follower);
    }

    public function deleteFollower(Profile $profile, Profile $follower): Profile
    {
        if (! $profile->followers()->find($follower->id)) {
            $profile->allNews()
                ->where('from_type', Profile::class)
                ->where('from_id', $follower->id)
                ->delete();
        }
        $profile->receivedRequests()->detach($follower->id);

        return $profile->load(['receivedRequests', 'followers']);
    }

    /**
     * @throws CannotUnfollowYourselfException
     * @throws FollowerNotFoundException
     */
    protected function validateInput(Profile $profile, Profile $follower): void
    {
        if ($profile->id == $follower->id) {
            throw new CannotUnfollowYourselfException('You cannot unfollow yourself');
        }

        if (! Profile::query()->find($follower->id)->exists()) {
            throw new ModelNotFoundException('Profile with id:'.$follower->id.' does not exist');
        }

        if (! $profile->receivedRequests()->find($follower->id)) {
            throw new FollowerNotFoundException('Follower not found');
        }
    }
}
