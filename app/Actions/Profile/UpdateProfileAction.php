<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Exceptions\CannotChangeDefaultProfileException;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Storage;
use Throwable;

class UpdateProfileAction
{

    /**
     * @throws Throwable
     * @throws NicknameAlreadyExistsException
     */
    public function execute(User $user, Profile $profile, UpdateProfileInput $input): Profile
    {
        $this->validateNickname($profile, $input);

        return DB::transaction(function () use ($user, $profile, $input): Profile {
            return $this->updateProfile($user, $profile, $input);
        });
    }


    /**
     * @throws CannotChangeDefaultProfileException
     */
    public function updateProfile(User $user, Profile $profile, UpdateProfileInput $input): Profile
    {
        $this->checkAndRestoreDefaults($user, $profile, $input);

        if ($input->mainImage && $profile->main_image) {
            Storage::disk('public')->delete($profile->main_image);
        }
        if ($input->secondaryImage && $profile->secondary_image) {
            Storage::disk('public')->delete($profile->secondary_image);
        }

        $mainImage = $input->mainImage?->store('profiles', 'public') ?? null;
        $secondaryImage = $input->secondaryImage?->store('backgrounds', 'public') ?? null;

        $profile->update([
            'nickname' => $input->nickname ?? $profile->nickname,
            'bio' => $input->bio ?? $profile->bio,
            'main_image' => $mainImage ?? $profile->main_image,
            'secondary_image' => $secondaryImage ?? $profile->secondary_image,
            'date_of_birth' => $input->dateOfBirth ?? $profile->date_of_birth,
            'default' => $input->default ?? $profile->default,
            'breed' => $input->breed ?? $profile->breed,
        ]);

        return $profile;
    }


    /**
     * @throws NicknameAlreadyExistsException
     */
    protected function validateNickname(Profile $profile, UpdateProfileInput $input): void
    {
        if ($input->nickname && $profile->nickname != $input->nickname && Profile::query()->where('nickname', $input->nickname)->exists()) {
            throw new NicknameAlreadyExistsException('Nickname already exists');
        }

    }

    /**
     * @throws CannotChangeDefaultProfileException
     */
    protected function checkAndRestoreDefaults(User $user, Profile $profile, UpdateProfileInput $input): void
    {
        if ($profile->default == $input->default) {
            return;
        }

        if ($user->profiles()->count() == 1 && !$input->default) {
            throw  new CannotChangeDefaultProfileException('Cannot change the default status of your last profile');
        }

        if (!$profile->default && $input->default) {
            $oldDefaultProfile = $user->profiles()->where('default', true)->first();
            $oldDefaultProfile->default = false;
            $oldDefaultProfile->save();
        }

        if ($profile->default && !$input->default) {
            $newDefaultProfile = $user->profiles()->firstWhere('id', '!=', $profile->id);
            $newDefaultProfile->default = true;
            $newDefaultProfile->save();
        }

    }
}
