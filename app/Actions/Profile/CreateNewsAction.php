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


    /**
     *
     * @param User $user
     * @param CreateNewsInput $input
     * @param Profile $profile
     * @return News
     */
    public function createNews(User $user, Profile $profile, CreateNewsInput $input): News
    {
        $news = new News([
            'type' => $input->type,
            'profile_id' => $input->profileId,
            'from' => $profile->id,
            'title' => $input->title,
            'body' => $input->body,
        ]);

        $news->save();

        return $news;
    }

    /**
     * @throws ProfileNotFoundException
     */
    protected function validateProfile(CreateNewsInput $input): void
    {
        if (!Profile::query()->find($input->profileId)) {
            throw new ProfileNotFoundException('Nickname already exists');
        }

    }
}
