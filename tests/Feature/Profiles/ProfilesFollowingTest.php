<?php

use App\Enum\NewsType;
use App\Http\Controllers\Api\FollowingController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can fetch followings', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profile->sentRequests()->attach($profileB, ['accepted' => true]);
    $profile->sentRequests()->attach($profileC, ['accepted' => true]);

    $response = getJson(action([FollowingController::class, 'index'], [
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
    );

    $profile = $profile->fresh();
    expect($profile)
        ->followers->not()->toBeNull();

});

it('can sent a follow request', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $response = postJson(action([FollowingController::class, 'store'], ['user' => $user->id, 'profile' => $profile->id,]), [
        'followerId' => $profileB->id,
    ]);
    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profile->id)
            ->has('following', length: 0)
            ->has('sentRequests', 1, fn(AssertableJson $json) => $json
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
    expect($profile->following()->get())->toBeEmpty();
    expect($profile->sentRequests()->find($profileB))->not()->toBeNull();

});

it('can delete a following', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profile->sentRequests()->attach($profileB, ['accepted' => true]);
    $profile->sentRequests()->attach($profileC, ['accepted' => true]);
    $profile->sentRequests()->attach($profile);

    $response = deleteJson(action([FollowingController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'following' => $profileB->id,
    ]));

    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profile->id)
            ->has('sentRequests', length: 2)
            ->has('following', 1, fn(AssertableJson $json) => $json
                ->where('id', $profileC->id)
                ->where('userId', $profileC->user_id)
                ->where('nickname', $profileC->nickname)
                ->where('type', $profileC->type->value)
                ->where('breed', $profileC->breed)
                ->where('mainImage', $profileC->main_image)
                ->where('secondaryImage', $profileC->secondary_image)
                ->etc()
            )
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile->following()->find($profileB))->toBeNull();
    expect($profile->sentRequests()->find($profileB))->toBeNull();

});

it('can delete a follow request', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $profile->sentRequests()->attach($profileB);
    $profile->sentRequests()->attach($profileC);
    $profile->sentRequests()->attach($profileD);

    $response = deleteJson(action([FollowingController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'following' => $profileB->id,
    ]));
    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $profile->id)
            ->has('sentRequests', length: 2)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile->following()->find($profileB))->toBeNull();
    expect($profile->sentRequests()->find($profileB))->toBeNull();

});

it('a follow request generate a news', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();

    $response = postJson(action([FollowingController::class, 'store'], ['user' => $user->id, 'profile' => $profile->id,]), [
        'followerId' => $profileB->id,
    ]);
    $response->assertSuccessful();

    $profileB = $profileB->fresh();
    expect($profileB)->news->not()->toBeNull();

    $new = $profileB->news()->first();

    expect($new->seen)->toBeFalse()
        ->and($new->profile_id)->toBe($profileB->id)
        ->and($new->from_id)->toBe($profile->id)
        ->and($new->from_type)->toBe(Profile::class)
        ->and($new->type)->toBe(NewsType::FOLLOW_REQUEST->value);

});
