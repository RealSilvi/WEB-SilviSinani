<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateProfileAction
{
    /**
     * @throws Throwable
     */
    public function execute(User $user, CreateProfileInput $input): Profile
    {
        $this->validateNickname($input);

        return DB::transaction(function () use ($user, $input): Profile {
            return $this->createProfile($user, $input);
        });
    }


    /**
     * Validate and create a newly registered profile.
     *
     * @param CreateProfileInput $input
     * @return Profile
     */
    public function createProfile(User $user, CreateProfileInput $input): Profile
    {
        $profile = new Profile([
            'nickname' => $input->nickname,
            'main_image' => $input->mainImage,
            'secondary_image' => $input->secondaryImage,
            'date_of_birth' => $input->dateOfBirth,
            'default' => $input->default,
            'user_id' => $user->id,
            'type' => $input->type,
            'breed' => $input->breed,
            'bio' => $input->bio,
        ]);

        $profile->save();

        return $profile;
    }

    /**
     * @throws NicknameAlreadyExistsException
     */
    protected function validateNickname(CreateProfileInput $input): void
    {
        if (Profile::query()->where('nickname', $input->nickname)->exists()) {
            throw new NicknameAlreadyExistsException('Nickname already exists');
        }

    }
}
