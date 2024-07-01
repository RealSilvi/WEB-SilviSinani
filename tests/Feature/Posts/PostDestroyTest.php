<?php

use App\Http\Controllers\Api\PostController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\deleteJson;

it('can delete a post', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(15)->create();
    $profile = $profile->fresh();
    $post = $profile->lastPost()->first();

    $response = deleteJson(action([PostController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'post' => $post->id,
    ]));

    $response->assertNoContent();

    expect($profile->posts()->get())->not()->toBeEmpty();
    expect($profile->posts()->where('id', $post->id)->get())->toBeEmpty();
    expect(Post::query()->where('id', $post->id)->get())->toBeEmpty();
});

it('can delete a full post', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();
    Post::factory()->for($profileA)->count(15)->create();
    $profileA = $profileA->fresh();
    $post = $profileA->lastPost()->first();

    Comment::factory()->for($profileB)->for($post)->count(15)->create();
    $profileA->postLikes()->attach($post);
    $profileB->postLikes()->attach($post);

    $response = deleteJson(action([PostController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profileA->id,
        'post' => $post->id,
    ]));

    $response->assertNoContent();

    expect(Comment::query()->where('post_id', $post->id)->get())->toBeEmpty();
    expect($profileA->postLikes)->toBeEmpty();
    expect($profileB->postLikes)->toBeEmpty();
    expect($profileA->posts()->get())->not()->toBeEmpty();
    expect($profileA->posts()->where('id', $post->id)->get())->toBeEmpty();
    expect(Post::query()->where('id', $post->id)->get())->toBeEmpty();
});
