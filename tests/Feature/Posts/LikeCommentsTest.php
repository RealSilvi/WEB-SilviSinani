<?php

use App\Enum\NewsType;
use App\Http\Controllers\Api\CommentLikeController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can fetch comment likes', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    $post = Post::factory()->for($profile)->create();
    $comment = Comment::factory()->for($profile)->for($post)->create();

    Profile::factory()->count(10)->create()->each(function (Profile $profile) use ($comment) {
        $profile->commentLikes()->attach($comment);
    });

    $response = getJson(action([CommentLikeController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'post' => $post->id,
        'comment' => $comment->id,
        'include' => []
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 10, fn(AssertableJson $json) => $json
            ->etc()
        )
        ->etc()
    );

    $comment = $comment->fresh();
    expect($comment->likes())
        ->not->toBeNull();
    expect($comment->likes()->count())
        ->toBe(10);

});

it('can create a comment like ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->create();
    $post = Post::factory()->for($profileA)->create();

    $profileB = Profile::factory()->create();
    $comment = Comment::factory()->for($profileB)->for($post)->create();

    $profileC = Profile::factory()->for($user)->create();

    $response = postJson(action([CommentLikeController::class, 'store'], [
        'user' => $user->id,
        'profile' => $profileC->id,
        'post' => $post->id,
        'comment' => $comment->id,
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('profileId', $profileB->id)
            ->where('postId', $post->id)
            ->has('likes', 1, fn(AssertableJson $json) => $json
                ->where('id', $profileC->id)
                ->etc()
            )
            ->etc()
        )
        ->etc()
    );

    $profileC = $profileC->fresh();
    expect($profileC->commentLikes()->first())
        ->not->toBeNull()
        ->id->toBe($comment->id);

});

it('can delete a comment like ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->create();
    $post = Post::factory()->for($profileA)->create();

    $profileB = Profile::factory()->create();
    $comment = Comment::factory()->for($profileB)->for($post)->create();

    $profileC = Profile::factory()->for($user)->create();
    $profileC->commentLikes()->attach($comment);
    $response = deleteJson(action([CommentLikeController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profileC->id,
        'post' => $post->id,
        'comment' => $comment->id,
    ]));
    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('profileId', $profileB->id)
            ->where('postId', $post->id)
            ->has('likes', length: 0)
            ->etc()
        )
        ->etc()
    );

    $profileC = $profileC->fresh();
    expect($profileC->commentLikes()->first())
        ->toBeNull();

});

it('a comment like can generate a news', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->create();
    $post = Post::factory()->for($profileA)->create();

    $profileB = Profile::factory()->create();
    $comment = Comment::factory()->for($profileB)->for($post)->create();

    $profileC = Profile::factory()->for($user)->create();

    $response = postJson(action([CommentLikeController::class, 'store'], [
        'user' => $user->id,
        'profile' => $profileC->id,
        'post' => $post->id,
        'comment' => $comment->id,
    ]));
    $response->assertSuccessful();

    $profileB = $profileB->fresh();
    expect($profileB)->news->not()->toBeNull();

    $new = $profileB->news()->first();

    expect($new->seen)->toBeFalse()
        ->and($new->profile_id)->toBe($profileB->id)
        ->and($new->from_id)->toBe($comment->id)
        ->and($new->from_type)->toBe(Comment::class)
        ->and($new->type)->toBe(NewsType::COMMENT_LIKE->value);

});
