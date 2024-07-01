<?php

use App\Http\Controllers\Api\FollowersController;
use App\Http\Controllers\Api\FollowingController;
use App\Models\News;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can fetch followers', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profileB->sentRequests()->attach($profile, ['accepted' => true]);
    $profileC->sentRequests()->attach($profile, ['accepted' => true]);

    $response = getJson(action([FollowersController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profile->id,
    ]));

    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 2, fn(AssertableJson $json) => $json
            ->where('id', $profileB->id)
            ->where('userId', $profileB->user_id)
            ->where('nickname', $profileB->nickname)
            ->where('type', $profileB->type->value)
            ->where('breed', $profileB->breed)
            ->where('mainImage', $profileB->main_image)
            ->where('secondaryImage', $profileB->secondary_image)
            ->etc()
        )
        ->etc()
    );

    $profile = $profile->fresh();
    expect($profile)
        ->followers->not()->toBeNull();

});

it('can store a follower/can accept follow requests', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profileB->sentRequests()->attach($profile);
    $profileC->sentRequests()->attach($profile);

    $response = postJson(action([FollowersController::class, 'store'], ['user' => $user->id, 'profile' => $profile->id,]), [
        'followerId' => $profileB->id,
    ]);
    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profile->id)
            ->has('receivedRequests', length: 2)
            ->has('followers', 1, fn(AssertableJson $json) => $json
                ->where('id', $profileB->id)
                ->where('userId', $profileB->user_id)
                ->where('nickname', $profileB->nickname)
                ->where('type', $profileB->type->value)
                ->where('breed', $profileB->breed)
                ->where('mainImage', $profileB->main_image)
                ->where('secondaryImage', $profileB->secondary_image)
                ->etc()
            )
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile->followers()->find($profileB))->not()->toBeNull();

});

it('can delete a follower', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profileB->sentRequests()->attach($profile, ['accepted' => true]);
    $profileD->sentRequests()->attach($profile, ['accepted' => true]);
    $profileC->sentRequests()->attach($profile);

    $response = deleteJson(action([FollowersController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'follower' => $profileB->id,
    ]));
    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profile->id)
            ->has('receivedRequests', length: 2)
            ->has('followers', 1, fn(AssertableJson $json) => $json
                ->where('id', $profileD->id)
                ->where('userId', $profileD->user_id)
                ->where('nickname', $profileD->nickname)
                ->where('type', $profileD->type->value)
                ->where('breed', $profileD->breed)
                ->where('mainImage', $profileD->main_image)
                ->where('secondaryImage', $profileD->secondary_image)
                ->etc()
            )
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile->followers()->find($profileB))->toBeNull();
    expect($profile->receivedRequests()->find($profileB))->toBeNull();

});

it('can decline a follower request', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profileB->sentRequests()->attach($profile);
    $profileD->sentRequests()->attach($profile);
    $profileC->sentRequests()->attach($profile);

    $response = deleteJson(action([FollowersController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'follower' => $profileB->id,
    ]));
    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profile->id)
            ->has('receivedRequests', length: 2)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile->followers()->find($profileB))->toBeNull();
    expect($profile->receivedRequests()->find($profileB))->toBeNull();

});

it('can delete a follow request news when follow request is accepted', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();

    postJson(action([FollowingController::class, 'store'], ['user' => $user->id, 'profile' => $profile->id,]), [
        'followerId' => $profileB->id,
    ]);

    $profileB = $profileB->fresh();
    $news = $profileB->news()->first();
    expect($news->from_id)->toBe($profile->id);

    Sanctum::actingAs($userB);

    postJson(action([FollowersController::class, 'store'], ['user' => $userB->id, 'profile' => $profileB->id,]), [
        'followerId' => $profile->id,
    ]);

    $profileB = $profileB->fresh();
    expect($profileB->allNews()->get())->toBeEmpty();

});

it('can delete a follow request news when follow request is rejected', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();

    postJson(action([FollowingController::class, 'store'], ['user' => $user->id, 'profile' => $profile->id,]), [
        'followerId' => $profileB->id,
    ]);

    $profileB = $profileB->fresh();
    expect($profileB->news->first->from->id)->toBe($profile->id);

    Sanctum::actingAs($userB);
    deleteJson(action([FollowersController::class, 'destroy'], [
        'user' => $userB->id,
        'profile' => $profileB->id,
        'follower' => $profile->id,
    ]));

    $profileB = $profileB->fresh();
    expect($profileB->allNews)->toBeEmpty();
});
