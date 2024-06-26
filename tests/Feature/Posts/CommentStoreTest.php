<?php

use App\Enum\NewsType;
use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it('can create a comment', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(15)->create();
    $profile = $profile->fresh();
    $post = $profile->lastPost()->first();

    $response = postJson(action(
        [CommentController::class, 'store'],
        ['user' => $user->id, 'profile' => $profile->id, 'post' => $post->id,'body' => 'Comment Test'],
    ));
    $response->assertCreated();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('postId', $post->id)
            ->where('body', 'Comment Test')
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile->comments()->first())
        ->not->toBeNull()
        ->body->toBe('Comment Test');

    $post = $post->fresh();
    expect($post->comments()->where('profile_id', $profile->id)->first())
        ->body->toBe('Comment Test');

});

it('a comment can generate a news', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(15)->create();
    $post = $profile->lastPost()->first();
    $profileB = Profile::factory()->for($user)->create();

    $response = postJson(action(
        [CommentController::class, 'store'],
        ['user' => $user->id, 'profile' => $profileB->id, 'post' => $post->id,'body' => 'Comment Test'],
    ));
    $response->assertCreated();

    $profileB = $profileB->fresh();
    expect($profile)->news->not()->toBeNull();

    $new = $profile->news()->first();

    expect($new->seen)->toBeFalse()
        ->and($new->profile_id)->toBe($profile->id)
        ->and($new->from_id)->toBe($response->json('data.id'))
        ->and($new->from_type)->toBe(Post::class)
        ->and($new->type)->toBe(NewsType::COMMENT->value);

});
