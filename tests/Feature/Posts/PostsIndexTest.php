<?php

use App\Http\Controllers\Api\PostController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('can fetch posts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(5)->create();
    $profile = $profile->fresh();
    $post = $profile->lastPost()->first();

    $response = getJson(action([PostController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'include' => [],
    ]));

    $response->assertOk();
    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', 5, fn (AssertableJson $json) => $json
            ->where('id', $post->id)
            ->where('image', $post->image)
            ->where('description', $post->description)
            ->where('profileId', $post->profile_id)
            ->etc()
        )
        ->etc()
    );
});

it('can fetch posts full', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();
    Post::factory()->for($profileA)->count(5)->create();
    $profileA = $profileA->fresh();
    $post = $profileA->lastPost()->first();

    Comment::factory()->for($profileB)->for($post)->count(5)->create();
    $profileA->postLikes()->attach($post);
    $profileB->postLikes()->attach($post);

    $response = getJson(action([PostController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profileA->id,
        'include' => [
            'profile',
            'comments',
            'likes',
        ],
    ]));

    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', 5, fn (AssertableJson $json) => $json
            ->where('id', $post->id)
            ->has('profile', fn (AssertableJson $json) => $json
                ->where('id', $profileA->id)
                ->etc())
            ->has('comments', 5, fn (AssertableJson $json) => $json
                ->where('profileId', $profileB->id)
                ->etc())
            ->has('likes', 2, fn (AssertableJson $json) => $json
                ->where('id', $profileA->id)
                ->etc())
            ->etc()
        )
        ->etc()
    );
});
