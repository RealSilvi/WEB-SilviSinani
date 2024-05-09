<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Data\UpdateProfileInput;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
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
     * @param User $user
     * @param CreateProfileInput $input
     * @return Profile
     */
    public function createProfile(User $user, CreateProfileInput $input): Profile
    {
        $input->nickname = Str::slug($input->nickname);
        $this->checkAndRestoreDefaults($user, $input);
        $images = $this->checkAndRestoreImages($input);


        $profile = new Profile([
            'nickname' => $input->nickname,
            'main_image' => $images['main_image'],
            'secondary_image' => $images['secondary_image'],
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
        if (Profile::query()->where('nickname', Str::lower($input->nickname))->exists()) {
            throw new NicknameAlreadyExistsException('Nickname already exists');
        }

    }

    protected function checkAndRestoreDefaults(User $user, CreateProfileInput $input): void
    {
        /** First profile */
        if ($user->profiles()->count() == 0) {
            $input->default = true;
            return;
        }
        /** New default profile */
        if ($input->default) {
            $oldDefaultProfile = $user->profiles()->where('default', true)->first();
            $oldDefaultProfile->default = false;
            $oldDefaultProfile->save();
        }
    }

    public function checkAndRestoreImages(CreateProfileInput $input): array
    {
        try {
            $mainImage = StoreImageOrStoreDefaultImageAction::execute(
                $input->mainImage,
                'profile.jpg',
                'profiles/' . $input->nickname,
                'utilities/profileDefault.jpg'
            );

            $secondaryImage = StoreImageOrStoreDefaultImageAction::execute(
                $input->secondaryImage,
                'background.jpg',
                'profiles/' . $input->nickname,
                'utilities/backgroundDefault.jpg'
            );
        } catch (FilesystemException) {
            $mainImage = asset('utilities/profileDefault.jpg');
            $secondaryImage = asset('utilities/backgroundDefault.jpg');
        }
        return [
            'main_image' => $mainImage,
            'secondary_image' => $secondaryImage,
        ];
    }

}
