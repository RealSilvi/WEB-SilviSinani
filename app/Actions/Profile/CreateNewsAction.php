<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateNewsInput;
use App\Exceptions\ProfileNotFoundException;
use App\Models\News;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateNewsAction
{
    /**
     * @throws Throwable
     */
    public function execute(User $user, Profile $profile, CreateNewsInput $input): News
    {
        $this->validateProfile($input);

        return DB::transaction(function () use ($user, $profile, $input): News {
            return $this->createNews($user, $profile, $input);
        });
    }

    public function createNews(User $user, Profile $profile, CreateNewsInput $input): News
    {
        $news = new News([
            'from_id' => $input->fromId,
            'from_type' => $input->fromType,
            'profile_id' => $input->profileId,
            'from_nickname' => $input->fromNickname,
            'type' => $input->type,
        ]);

        $news->save();

        return $news;
    }

    /**
     * @throws ProfileNotFoundException
     */
    protected function validateProfile(CreateNewsInput $input): void
    {
        if (! Profile::query()->find($input->profileId)) {
            throw new ProfileNotFoundException('Profile not found');
        }

    }
}
