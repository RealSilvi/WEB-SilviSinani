<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\ProfileFollowInput;
use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Enum\NewsType;
use App\Exceptions\CannotFollowYourselfException;
use App\Exceptions\FollowerNotFoundException;
use App\Exceptions\FollowRequestNotFoundException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\News;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
use Throwable;

class CreateProfileFollowerAction
{
    /**
     * @throws Throwable
     */
    public function execute(Profile $profile, ProfileFollowInput $input): Profile
    {
        $this->validateInput($profile, $input);

        return $this->acceptRequest($profile, $input);
    }

    public function acceptRequest(Profile $profile, ProfileFollowInput $input): Profile
    {
        $request_id = $profile->receivedRequests()->where('follower_id', $input->followerId)->first()->id;
        $profile->receivedRequests()->updateExistingPivot($request_id, ['accepted' => true]);
        $profile->allNews()
            ->where('from_type', Profile::class)
            ->where('from_id', $input->followerId)
            ->delete();
        return $profile->load(['receivedRequests', 'followers']);
    }

    /**
     * @throws CannotFollowYourselfException
     * @throws FollowerNotFoundException
     */
    protected function validateInput(Profile $profile, ProfileFollowInput $input): void
    {
        if ($profile->id == $input->followerId) {
            throw new CannotFollowYourselfException('You cannot follow yourself');
        }

        if (!Profile::query()->find($input->followerId)->exists()) {
            throw new ModelNotFoundException('Profile with id:' . $input->followerId . ' does not exist');
        }

        if (!$profile->receivedRequests()->find($input->followerId)->exists()) {
            throw new FollowerNotFoundException('Follow request not found');
        }
    }
}
