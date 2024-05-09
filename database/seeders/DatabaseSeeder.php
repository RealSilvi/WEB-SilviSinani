<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);

        $users = User::factory(50)->create();

        foreach ($users as $user) {
            $howManyProfiles = rand(0, 4);
            for ($i = 0; $i <= $howManyProfiles; $i++) {

                Profile::factory()->for($user)->realImages()->create([
                    'default' => $i===0,
                ]);
            }
        }
    }
}
