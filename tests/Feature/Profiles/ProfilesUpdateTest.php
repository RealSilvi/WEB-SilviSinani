<?php

use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\patch;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

it('can update a profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $date = fake()->date();
    $mainUrl = fake()->url;
    $secondaryUrl = fake()->url;

    $response = patch(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profile->id)]), [
        'nickname' => 'Scott',
        'dateOfBirth' => $date,
        'breed' => ProfileBreedDog::GOLDEN_RETRIEVER->value,
        'mainImage' => $mainUrl,
        'secondaryImage' => $secondaryUrl,
        'bio' => 'Scott it is an awesome dog.',
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->has('type')
            ->where('id', $profile->id)
            ->where('nickname', 'Scott')
            ->where('userId', $user->id)
            ->where('dateOfBirth', $date)
            ->where('breed', ProfileBreedDog::GOLDEN_RETRIEVER->value)
            ->where('mainImage', $mainUrl)
            ->where('secondaryImage', $secondaryUrl)
            ->where('bio', 'Scott it is an awesome dog.')
            ->etc()
        )
    );

    $user = $user->fresh();
    $profile = $profile->fresh();

    expect($profile)
        ->user_id->toBe($user->id)
        ->nickname->toBe('Scott')
        ->date_of_birth->toBe($date)
        ->breed->toBe(ProfileBreedDog::GOLDEN_RETRIEVER->value)
        ->main_image->toBe($mainUrl)
        ->secondary_image->toBe($secondaryUrl)
        ->bio->toBe('Scott it is an awesome dog.')
        ->type->not()->toBeNull();
});
