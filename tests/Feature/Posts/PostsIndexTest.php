<?php

use App\Http\Controllers\Api\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

it('can fetch posts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();

    $response = getJson(action([ProfileController::class, 'index'], [
        'user' => $user->id,
        'include' => []
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 2, fn(AssertableJson $json) => $json
            ->where('nickname', $profileA->nickname)
            ->where('default', $profileA->default)
            ->where('userId', $user->id)
            ->where('type', $profileA->type->value)
            ->where('dateOfBirth', $profileA->date_of_birth)
            ->where('breed', $profileA->breed)
            ->where('mainImage', $profileA->main_image)
            ->where('secondaryImage', $profileA->secondary_image)
            ->where('bio', $profileA->bio)
            ->etc()
        )
        ->etc()
    );
});
//
//it('can fetch profiles full', function () {
//    $user = User::factory()->create();
//    Sanctum::actingAs($user);
//
//    $profileA = Profile::factory()->for($user)->create();
//    $profileB = Profile::factory()->for($user)->create();
//
//    $response = getJson(action([ProfileController::class, 'index'], [
//        'user' => $user->id,
//        'include' => ['user']
//    ]));
//
//    $response->assertOk();
//
//    $response->assertJson(fn(AssertableJson $json) => $json
//        ->has('data', 2, fn(AssertableJson $json) => $json
//            ->where('id', $profileA->id)
//            ->where('nickname', $profileA->nickname)
//            ->where('default', $profileA->default)
//            ->where('userId', $user->id)
//            ->where('type', $profileA->type->value)
//            ->where('dateOfBirth', $profileA->date_of_birth)
//            ->where('breed', $profileA->breed)
//            ->where('mainImage', $profileA->main_image)
//            ->where('secondaryImage', $profileA->secondary_image)
//            ->where('bio', $profileA->bio)
//            ->has('user', fn(AssertableJson $json) => $json
//                ->where('id', $user->id)
//                ->where('firstName', $user->first_name)
//                ->where('lastName', $user->last_name)
//                ->where('dateOfBirth', $user->date_of_birth)
//                ->where('email', $user->email)
//                ->etc()
//            )
//            ->etc()
//        )
//        ->etc()
//    );
//});
//
//it('can not fetch profiles when user does not have any', function () {
//    $user = User::factory()->create();
//    Sanctum::actingAs($user);
//
//    $response = getJson(action([ProfileController::class, 'index'], ['user' => $user->id]));
//
//    $response->assertNotAcceptable();
//
//    expect($response->json())
//        ->error->toBe(true)
//        ->message->toBe('The user does not have profiles yet.');
//});
