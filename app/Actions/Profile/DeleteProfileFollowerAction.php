<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Exceptions\CannotUnfollowYourselfException;
use App\Exceptions\FollowerNotFoundException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

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
        $profile->receivedRequests()->detach($follower->id);
        return $profile->load(['receivedRequests','followers']);
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

        if (!Profile::query()->find($follower->id)->exists()) {
            throw new ModelNotFoundException('Profile with id:' . $follower->id . ' does not exist');
        }

        if (!$profile->receivedRequests()->find($follower->id)->exists()) {
            throw new FollowerNotFoundException('Follower not found');
        }
    }
}
