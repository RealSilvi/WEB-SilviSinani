<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(50)->create();
        foreach ($users as $user) {
            $howManyProfiles = rand(0, 4);
            for ($i = 0; $i <= $howManyProfiles; $i++) {
                Profile::factory()->for($user)->create([
                    'default' => $i === 0,
                ]);
            }
        }
    }
}
