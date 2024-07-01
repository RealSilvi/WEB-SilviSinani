<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $userAdmin = User::factory()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $userTest = User::factory()->create([
            'first_name' => 'test',
            'last_name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        Profile::factory()->create([
            'user_id' => $userAdmin->id,
            'nickname' => 'admin',
            'default' => true,
        ]);

        Profile::factory()->create([
            'user_id' => $userTest->id,
            'nickname' => 'test',
            'default' => true,
        ]);
    }
}
