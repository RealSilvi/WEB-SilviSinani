<?php

use App\Enum\NewsType;
use App\Http\Controllers\Api\PostLikeController;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can fetch post likes', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    $post = Post::factory()->for($profile)->create();

    Profile::factory()->count(10)->create()->each(function (Profile $profile) use ($post) {
        $profile->postLikes()->attach($post);
    });

    $response = getJson(action([PostLikeController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'post' => $post->id,
        'include' => [],
    ]));

    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', 10, fn (AssertableJson $json) => $json
            ->etc()
        )
        ->etc()
    );

    $post = $post->fresh();
    expect($post->likes())
        ->not->toBeNull();
    expect($post->likes()->count())
        ->toBe(10);

});

it('can create a post like ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->create();
    $post = Post::factory()->for($profileA)->create();

    $profileB = Profile::factory()->for($user)->create();

    $response = postJson(action([PostLikeController::class, 'store'], [
        'user' => $user->id,
        'profile' => $profileB->id,
        'post' => $post->id,
    ]));

    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', fn (AssertableJson $json) => $json
            ->where('id', $post->id)
            ->where('profileId', $profileA->id)
            ->has('likes', 1, fn (AssertableJson $json) => $json
                ->where('id', $profileB->id)
                ->etc()
            )
            ->etc()
        )
        ->etc()
    );

    $profileB = $profileB->fresh();
    expect($profileB->postLikes()->first())
        ->not->toBeNull()
        ->id->toBe($post->id);

});

it('can delete a post like ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->create();
    $post = Post::factory()->for($profileA)->create();

    $profileB = Profile::factory()->for($user)->create();
    $profileB->postLikes()->attach($post);
    $response = deleteJson(action([PostLikeController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profileB->id,
        'post' => $post->id,
    ]));
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', fn (AssertableJson $json) => $json
            ->where('profileId', $profileA->id)
            ->where('id', $post->id)
            ->has('likes', length: 0)
            ->etc()
        )
        ->etc()
    );

    $profileB = $profileB->fresh();
    expect($profileB->postLikes()->first())
        ->toBeNull();

});

it('a post like can generate a news', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->create();
    $post = Post::factory()->for($profileA)->create();

    $profileB = Profile::factory()->for($user)->create();

    $response = postJson(action([PostLikeController::class, 'store'], [
        'user' => $user->id,
        'profile' => $profileB->id,
        'post' => $post->id,
    ]));

    $response->assertSuccessful();

    $profileA = $profileA->fresh();
    expect($profileA)->news->not()->toBeNull();

    $new = $profileA->news()->first();

    expect($new->seen)->toBeFalse()
        ->and($new->profile_id)->toBe($profileA->id)
        ->and($new->from_id)->toBe($post->id)
        ->and($new->from_type)->toBe(Post::class)
        ->and($new->type)->toBe(NewsType::POST_LIKE->value);

});
