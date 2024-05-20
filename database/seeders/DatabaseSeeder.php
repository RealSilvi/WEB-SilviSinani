<?php

namespace Database\Seeders;

use App\Enum\ProfileType;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $userAdmin = User::factory()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);
        $userTest = User::factory()->create([
            'first_name' => 'test',
            'last_name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        Profile::factory()->realImages()->create([
            'user_id' => $userAdmin->id,
            'nickname' => 'admin',
            'default' => true,
        ]);

        Profile::factory()->realImages()->create([
            'user_id' => $userTest->id,
            'nickname' => 'test',
            'default' => true,
        ]);


        $users = User::factory(50)->create();
        foreach ($users as $user) {
            $howManyProfiles = rand(0, 4);
            for ($i = 0; $i <= $howManyProfiles; $i++) {
                Profile::factory()->for($user)->realImages()->create([
                    'default' => $i === 0,
                ]);
            }
        }

        $profiles = Profile::query()->get();
        for ($i = 0; $i <= 400; $i++) {
            $profileA = $profiles->random();
            $profileB = $profiles->random();
            if ($profileA->id !== $profileB->id && !$profileA->following()->find($profileB)) {
                $profileA->sentRequests()->attach($profileB, ['accepted' => true]);
            }
        }
    }
}
