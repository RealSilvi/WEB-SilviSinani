<?php

use App\Enum\NewsType;
use App\Http\Controllers\Api\FollowersController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\Api\NewsController;
use App\Models\News;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can store a news ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $response = postJson(action([NewsController::class, 'store'], ['user' => $user->id, 'profile' => $profileB->id,]), [
        'profileId' => $profile->id,
        'type' => NewsType::FOLLOW_REQUEST->value,
    ]);
    $response->assertCreated();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('from', $profileB->id)
            ->where('type', NewsType::FOLLOW_REQUEST->value)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile)->news->not()->toBeNull();

});

it('can seen correctly ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    News::factory()->for($profile)->create(['type' => NewsType::FOLLOW_REQUEST]);
    News::factory()->for($profile)->create(['type' => NewsType::FOLLOW_REQUEST]);
    News::factory()->for($profile)->create(['type' => NewsType::FOLLOW_REQUEST]);

    $response = postJson(action([NewsController::class, 'seeAll'], ['user' => $user->id, 'profile' => $profile->id,]));
    $response->assertNoContent();

    $profile = $profile->fresh();

    $profile->news()->get()->each(fn(News $new) => expect($new->seen)->toBeTrue());
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
        ->and($new->from)->toBe($profile->id)
        ->and($new->type)->toBe(NewsType::FOLLOW_REQUEST);

});
