<?php

namespace App\Http\Controllers;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Profile\CreateProfileAction;
use App\Actions\Profile\DeleteProfileAction;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Response;
use Throwable;

class ProfileController
{
    /**
     * @throws Throwable
     */
    public function store(User $user, CreateProfileInput $input, CreateProfileAction $action): ProfileResource
    {
        $profile = $action->execute($user,$input);

        return new ProfileResource($profile);
    }

    public function destroy(User $user, Profile $profile, DeleteProfileAction $action): Response
    {
        $action->execute($user, $profile);

        return response()->noContent();
    }


}
