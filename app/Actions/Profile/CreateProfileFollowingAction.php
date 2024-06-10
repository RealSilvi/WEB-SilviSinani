<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateNewsInput;
use App\Actions\Data\ProfileFollowInput;
use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Enum\NewsType;
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
    public function execute(User $user, Profile $profile, ProfileFollowInput $input): Profile
    {
        $this->validateInput($profile, $input);
        $profile = $this->sendRequest($profile, $input);
        $this->sendNews($user, $profile, $input);
        return $profile;
    }

    public function sendRequest(Profile $profile, ProfileFollowInput $input): Profile
    {
        $profile->sentRequests()->attach($input->followerId);

        return $profile->load(['sentRequests', 'following']);
    }

    /**
     * @throws Throwable
     */
    public function sendNews(User $user, Profile $profile, ProfileFollowInput $input): void
    {
        app(CreateNewsAction::class)->execute($user, $profile, new CreateNewsInput(
            fromId: $profile->id,
            fromType: Profile::class,
            profileId: $input->followerId,
            type: NewsType::FOLLOW_REQUEST,
            title: $profile->nickname . ' wants to follow you.',
        ));
    }

    /**
     * @throws CannotFollowYourselfException
     */
    protected
    function validateInput(Profile $profile, ProfileFollowInput $input): void
    {
        if ($profile->id == $input->followerId) {
            throw new CannotFollowYourselfException('You cannot follow yourself');
        }

        if (!Profile::query()->find($input->followerId)->exists()) {
            throw new ModelNotFoundException('Profile with id:' . $input->followerId . ' does not exist');
        }

    }
}
