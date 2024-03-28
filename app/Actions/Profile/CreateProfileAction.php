<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateProfileAction
{
    /**
     * @throws Throwable
     */
    public function execute(CreateProfileInput $input): Profile
    {
        return DB::transaction(function () use ($input): Profile {
            return  $this->createProfile($input);
        });
    }


    /**
     * Validate and create a newly registered profile.
     *
     * @param CreateProfileInput $input
     * @return Profile
     */
    public function createProfile(CreateProfileInput $input): Profile
    {
        $profile = new Profile([
            'user_id' => $input->userId,
            'nickname' => $input->nickname,
            'date_of_birth' => $input->dateOfBirth,
            'bio' => $input->bio,
        ]);

        $profile->save();

        return $profile;
    }

}
