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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
use Nette\FileNotFoundException;
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
     * @throws CannotChangeDefaultProfileException|FilesystemException
     */
    public function updateProfile(User $user, Profile $profile, UpdateProfileInput $input): Profile
    {
        $input->nickname = Str::slug($input->nickname ?? $profile->nickname);
        $this->checkAndRestoreDefaults($user, $profile, $input);
        $this->checkNicknamesAndRestoreStoragePaths($profile, $input);

        $images = $this->checkAndRestoreImages($input, $profile);

        $profile->update([
            'nickname' => $input->nickname,
            'bio' => $input->bio ?? $profile->bio,
            'main_image' => $images['main_image'],
            'secondary_image' => $images['secondary_image'],
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

        if ($profile->default === $input->default || $input->default === null) {
            return;
        }

        if ($user->profiles()->count() === 1 && !$input->default) {
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

    /**
     * @throws FilesystemException
     */
    public function checkNicknamesAndRestoreStoragePaths(Profile $profile, UpdateProfileInput $input): void
    {
        if ($profile->nickname === $input->nickname || !$input->nickname) {
            return;
        }

        foreach (Storage::disk('public')->allFiles('profiles/' . $profile->nickname) as $file) {
            Storage::disk('public')->move(
                $file,
                Str::replace($profile->nickname, $input->nickname, $file),
            );
        }
        Storage::disk('public')->deleteDirectory('profiles/' . $profile->nickname);

        $profile->main_image = Str::replace($profile->nickname, $input->nickname, $profile->main_image);
        $profile->secondary_image = Str::replace($profile->nickname, $input->nickname, $profile->secondary_image);
    }

    public function checkAndRestoreImages(UpdateProfileInput $input, Profile $profile): array
    {
        try {
            if ($input->mainImage) {
                $mainImage = StoreImageOrStoreDefaultImageAction::execute(
                    $input->mainImage,
                    'profile.jpg',
                    'profiles/' . $input->nickname,
                    'utilities/profileDefault.jpg'
                );
            }
            if ($input->secondaryImage) {
                $secondaryImage = StoreImageOrStoreDefaultImageAction::execute(
                    $input->secondaryImage,
                    'background.jpg',
                    'profiles/' . $input->nickname,
                    'utilities/backgroundDefault.jpg'
                );
            }
        } catch (FilesystemException|FileNotFoundException) {
            if ($input->mainImage) {
                $mainImage = asset('utilities/profileDefault.jpg');
            }
            if ($input->secondaryImage) {
                $secondaryImage = asset('utilities/backgroundDefault.jpg');
            }
        }
        return [
            'main_image' => $mainImage ?? $profile->main_image,
            'secondary_image' => $secondaryImage ?? $profile->secondary_image,
        ];
    }
}
