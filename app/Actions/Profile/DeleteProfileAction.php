<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeleteProfileAction
{

    public function execute(User $user, Profile $profile): void
    {
        DB::transaction(function () use ($user, $profile): void {
            $this->checkAndRestoreDefaults($user, $profile);
            $profile->user()->dissociate();

            $profile->delete();
        });
    }

    protected function checkAndRestoreDefaults(User $user, Profile $profile): void
    {
        if (!$profile->default) {
            return;
        }
        $newDefaultProfile = $user->profiles()->firstWhere('id', '!=', $profile->id);
        $newDefaultProfile->default = true;
        $newDefaultProfile->save();

    }
}
