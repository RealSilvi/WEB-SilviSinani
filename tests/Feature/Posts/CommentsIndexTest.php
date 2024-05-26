<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

it('can fetch comments', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(15)->create();
    $profile = $profile->fresh();
    $post = $profile->lastPost()->first();
    $comment = Comment::factory()->for($profile)->for($post)->create(['body' => 'commentA']);
    Comment::factory()->for($post)->create(['body' => 'commentB']);
    Comment::factory()->for($post)->create(['body' => 'commentC']);

    $response = getJson(action([CommentController::class, 'show'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'post' => $post->id,
        'comment' => $comment->id,
        'include' => []
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('postId', $post->id)
            ->where('body', 'commentA')
            ->etc()
        )
        ->etc()
    );

    $profile = $profile->fresh();
    expect($profile->comments()->first())
        ->not->toBeNull()
        ->body->toBe('commentA');
    $post = $post->fresh();
    expect($post->comments()->where('profile_id', $profile->id)->first())
        ->not->toBeNull();

});

it('can fetch comments full', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(15)->create();
    $profile = $profile->fresh();
    $post = $profile->lastPost()->first();
    $comment = Comment::factory()->for($profile)->for($post)->create(['body' => 'commentA']);
    Comment::factory()->for($post)->create(['body' => 'commentB']);
    Comment::factory()->for($post)->create(['body' => 'commentC']);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();
    $profileA->commentLikes()->attach($comment);
    $profileB->commentLikes()->attach($comment);

    $response = getJson(action([CommentController::class, 'show'], [
        'user' => $user->id,
        'profile' => $profileA->id,
        'post' => $post->id,
        'comment' => $comment->id,
        'include' => [
            'profile',
            'post',
            'likes',
        ]
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('id', $comment->id)
            ->has('profile', fn(AssertableJson $json) => $json
                ->where('id', $profile->id)
                ->etc())
            ->has('post', fn(AssertableJson $json) => $json
                ->where('id', $post->id)
                ->etc())
            ->has('likes', 2, fn(AssertableJson $json) => $json
                ->where('id', $profileA->id)
                ->etc())
            ->etc()
        )
        ->etc()
    );
});
