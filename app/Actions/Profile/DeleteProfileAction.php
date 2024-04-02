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
        DB::transaction(function () use ($profile): void {
            $profile->user()->dissociate();

            $profile->delete();
        });
    }
}
