<?php

namespace App\Http\Controllers;

use App\Actions\Data\CreateProfileInput;
use App\Actions\Profile\CreateProfileAction;
use App\Http\Resources\ProfileResource;
use Throwable;

class ProfileController
{
    /**
     * @throws Throwable
     */
    public function store(CreateProfileInput $input, CreateProfileAction $action): ProfileResource
    {
        $profile = $action->execute($input);

        return new ProfileResource($profile);
    }


}
