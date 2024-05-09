<?php

use App\Http\Controllers\Api\ProfileController;
use App\Models\Profile;
use App\Models\User;
use App\Support\ImageGenerator;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;

it('can generate a random image', function () {
    $nickname = fake()->userName();
    $endLocation = 'profiles/' . $nickname;
    $filename = 'profile.jpg';
    $filters = ['pet', 'green'];
    $generator = new ImageGenerator();

    $imagePath = $generator->generate($endLocation, $filename, $filters);

    Storage::disk('public')->assertExists($imagePath);
    Storage::disk('public')->deleteDirectory($endLocation);
});
