<?php

namespace App\Http\Controllers;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Profile\CreateProfileAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Exceptions\CannotDeleteDefaultProfileException;
use App\Exceptions\ToManyProfilesException;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Response;
use Throwable;

class ProfileController
{
    /**
     * @throws ToManyProfilesException|Throwable
     */
    public function store(User $user, CreateProfileInput $input, CreateProfileAction $action): ProfileResource
    {
        if ($user->profiles()->count() > 3) {
            throw new ToManyProfilesException('Already 4 profiles existing for this user. Delete one of them before storing a new one');
        }

        $profile = $action->execute($user, $input);

        return new ProfileResource($profile);
    }

    /**
     * @throws CannotDeleteDefaultProfileException
     */
    public function destroy(User $user, Profile $profile, DeleteProfileAction $action): Response
    {
        if ($user->profiles()->count() < 2) {
            throw new CannotDeleteDefaultProfileException('Cannot delete your last profile. If you want you can delete the user.');
        }
        $action->execute($user, $profile);

        return response()->noContent();
    }


}
