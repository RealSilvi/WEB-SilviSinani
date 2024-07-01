<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\News;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class SeeAllNewsAction
{
    /**
     * @throws Throwable
     */
    public function execute(User $user, Profile $profile): void
    {
        DB::transaction(function () use ($user, $profile): void {
            $this->seeAllNews($user, $profile);
        });
    }

    public function seeAllNews(User $user, Profile $profile): void
    {
        $profile->news()->each(function (News $new) {
            $new->update([
                'seen' => true,
                'seen_at' => now(),
            ]);
        });

    }
}
