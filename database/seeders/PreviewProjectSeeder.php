<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PreviewProjectSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
           DatabaseSeeder::class,
           RealImageSeeder::class,
        ]);
    }
}
