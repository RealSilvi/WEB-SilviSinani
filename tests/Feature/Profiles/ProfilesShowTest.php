<?php

use App\Http\Controllers\Api\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

it('can fetch a single profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();

    $response = getJson(action([ProfileController::class, 'show'], [
        'user' => $user->id,
        'profile' => ($profileB->id),
        'include' => []
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('nickname', $profileB->nickname)
            ->where('default', $profileB->default)
            ->where('userId', $user->id)
            ->where('type', $profileB->type->value)
            ->where('dateOfBirth', $profileB->date_of_birth)
            ->where('breed', $profileB->breed)
            ->where('mainImage', $profileB->main_image)
            ->where('secondaryImage', $profileB->secondary_image)
            ->where('bio', $profileB->bio)
            ->etc()
        )
        ->etc()
    );
});

it('can fetch a single profile full', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();

    $response = getJson(action([ProfileController::class, 'show'], [
        'user' => $user->id,
        'profile' => ($profileB->id),
        'include' => ['user']
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profileB->id)
            ->where('nickname', $profileB->nickname)
            ->where('default', $profileB->default)
            ->where('userId', $user->id)
            ->where('type', $profileB->type->value)
            ->where('dateOfBirth', $profileB->date_of_birth)
            ->where('breed', $profileB->breed)
            ->where('mainImage', $profileB->main_image)
            ->where('secondaryImage', $profileB->secondary_image)
            ->where('bio', $profileB->bio)
            ->has('user', fn(AssertableJson $json) => $json
                ->where('id', $user->id)
                ->where('firstName', $user->first_name)
                ->where('lastName', $user->last_name)
                ->where('dateOfBirth', $user->date_of_birth)
                ->where('email', $user->email)
                ->etc()
            )
            ->etc()
        )
        ->etc()
    );
});
