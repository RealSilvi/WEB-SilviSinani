<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\ProfileFollowInput;
use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Exceptions\CannotFollowYourselfException;
use App\Exceptions\FollowRequestNotFoundException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
use Throwable;

class CreateProfileFollowingAction
{
    /**
     * @throws Throwable
     */
    public function execute(Profile $profile, ProfileFollowInput $input): Profile
    {
        $this->validateInput($profile, $input);

        return $this->sendRequest($profile, $input);
    }

    public function sendRequest(Profile $profile, ProfileFollowInput $input): Profile
    {
        $profile->sentRequests()->attach($input->followerId);

        return $profile->load(['sentRequests','following']);
    }

    /**
     * @throws CannotFollowYourselfException
     */
    protected function validateInput(Profile $profile, ProfileFollowInput $input): void
    {
        if ($profile->id == $input->followerId) {
            throw new CannotFollowYourselfException('You cannot follow yourself');
        }

        if (!Profile::query()->find($input->followerId)->exists()) {
            throw new ModelNotFoundException('Profile with id:' . $input->followerId . ' does not exist');
        }

    }
}
