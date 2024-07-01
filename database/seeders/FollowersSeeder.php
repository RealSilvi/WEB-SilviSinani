<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class FollowersSeeder extends Seeder
{
    public function run(): void
    {

        $profiles = Profile::query()->get();
        for ($i = 0; $i <= 400; $i++) {
            $profileA = $profiles->random();
            $profileB = $profiles->random();
            if ($profileA->id !== $profileB->id && ! $profileA->following()->find($profileB)) {
                $profileA->sentRequests()->attach($profileB, ['accepted' => true]);
            }
        }
    }
}
