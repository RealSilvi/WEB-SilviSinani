<?php

use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it('can create a basic profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $date = now();
    $response = postJson(action([ProfileController::class, 'store']), [
        'userId' => $user->id,
        'default' => true,
        'nickname' => 'Scott',
        'type' => ProfileType::DOG,
    ]);

    $response->assertCreated();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('userId', $user->id)
            ->where('default', true)
            ->where('nickname', 'Scott')
            ->where('type', ProfileType::DOG->value)
            ->etc()
        )
    );

    $user->fresh();
    expect($user)
        ->not()->toBeNull()
        ->profiles->not()->toBeNull();

    $profile = $user->profiles()->find($response->json('data.id'));
    expect($profile)
        ->user_id->toBe($user->id)
        ->default->toBe(true)
        ->nickname->toBe('Scott')
        ->type->toBe(ProfileType::DOG);


});

it('can create a full profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $date = fake()->date();
    $mainUrl=fake()->url;
    $secondaryUrl=fake()->url;

    $response = postJson(action([ProfileController::class, 'store']), [
        'nickname' => 'Scott',
        'default' => true,
        'userId' => $user->id,
        'type' => ProfileType::DOG,
        'dateOfBirth' => $date,
        'breed' => ProfileBreedDog::GOLDEN_RETRIEVER,
        'mainImage' => $mainUrl,
        'secondaryImage' => $secondaryUrl,
        'bio' => 'Scott it is an awesome dog.',
    ]);

    $response->assertCreated();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('nickname', 'Scott')
            ->where('default', true)
            ->where('userId', $user->id)
            ->where('type', ProfileType::DOG->value)
            ->where('dateOfBirth', $date)
            ->where('breed', ProfileBreedDog::GOLDEN_RETRIEVER->value)
            ->where('mainImage', $mainUrl)
            ->where('secondaryImage', $secondaryUrl)
            ->where('bio', 'Scott it is an awesome dog.')
            ->etc()
        )
    );

});
