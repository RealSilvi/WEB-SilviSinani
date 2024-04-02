<?php

use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it('can create a basic profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
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
    $mainUrl = fake()->url;
    $secondaryUrl = fake()->url;

    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'Scott',
        'default' => true,
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

it('can not create a profile with the same nickname', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Profile::factory()->for($user)->create(['nickname' => 'Scott']);
    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'default' => true,
        'nickname' => 'Scott',
        'type' => ProfileType::DOG,
    ]);

    $response->assertNotAcceptable();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Nickname already exists');
});
