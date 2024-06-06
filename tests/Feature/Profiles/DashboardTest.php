<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FollowersController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can fetch dashboard posts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create(['type'=>\App\Enum\ProfileType::DOG]);

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::DOG]);
    $profileC = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::DOG]);
    $profileD = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::BIRD]);
    $profileE = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::CAT]);

    $profile->sentRequests()->attach($profileB, ['accepted' => true]);
    $profile->sentRequests()->attach($profileC, ['accepted' => true]);

    Post::factory()->count(4)->for($profileB)->create();
    Post::factory()->count(5)->for($profileC)->create();
    Post::factory()->count(5)->for($profileD)->create();
    $lastPost = Post::factory()->for($profileB)->create(['created_at' => now()->addDay()]);

    $response = getJson(action([DashboardController::class, 'show'], [
        'user' => $user->id,
        'profile' => $profile->id,
    ]));

    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 10, fn(AssertableJson $json) => $json
            ->where('id', $lastPost->id)
            ->where('profileId', $lastPost->profile_id)
            ->etc()
        )
    );

});

it('can fetch dashboard posts full', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create(['type'=>\App\Enum\ProfileType::DOG]);

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::DOG]);
    $profileC = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::DOG]);
    $profileD = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::BIRD]);
    $profileE = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::CAT]);

    $profile->sentRequests()->attach($profileB, ['accepted' => true]);
    $profile->sentRequests()->attach($profileC, ['accepted' => true]);

    Post::factory()->count(4)->for($profileB)->create();
    Post::factory()->count(5)->for($profileC)->create();
    Post::factory()->count(5)->for($profileD)->create();
    $lastPost = Post::factory()->for($profileB)->create(['created_at' => now()->addDay()]);

    Comment::factory()->for($profileB)->for($lastPost)->count(15)->create();
    $profileB->postLikes()->attach($lastPost);
    $profileC->postLikes()->attach($lastPost);


    $response = getJson(action([DashboardController::class, 'show'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'include' => [
            'profile',
            'comments',
            'likes'
        ]
    ]));


    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 10, fn(AssertableJson $json) => $json
            ->where('id', $lastPost->id)
            ->where('profileId', $lastPost->profile_id)
            ->has('profile', fn(AssertableJson $json) => $json
                ->where('id', $lastPost->profile_id)
                ->etc())
            ->has('comments', 15, fn(AssertableJson $json) => $json
                ->where('profileId', $profileB->id)
                ->etc())
            ->has('likes', 2, fn(AssertableJson $json) => $json
                ->where('id', $profileB->id)
                ->etc())
            ->etc()
        )
    );

});

it('can fetch dashboard advice posts ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create(['type'=>\App\Enum\ProfileType::DOG]);

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::DOG]);
    $profileC = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::CAT]);
    $profileD = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::DOG]);
    $profileE = Profile::factory()->for($userB)->create(['type'=>\App\Enum\ProfileType::CAT]);

    $profile->sentRequests()->attach($profileB, ['accepted' => true]);
    $profile->sentRequests()->attach($profileC, ['accepted' => true]);

    Post::factory()->count(4)->for($profileB)->create();
    Post::factory()->count(5)->for($profileC)->create();
    Post::factory()->count(5)->for($profileD)->create();
    $lastPost = Post::factory()->for($profileB)->create(['created_at' => now()->addDay()]);

    $response = getJson(action([DashboardController::class, 'show'], [
        'user' => $user->id,
        'profile' => $profile->id,
    ]));

    $response->assertSuccessful();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 15, fn(AssertableJson $json) => $json
            ->where('id', $lastPost->id)
            ->where('profileId', $lastPost->profile_id)
            ->etc()
        )
    );

});
