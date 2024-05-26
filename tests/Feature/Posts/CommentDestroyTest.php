<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;

it('can delete a comment', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);


    $profile = Profile::factory()->for($user)->create();
    Post::factory()->for($profile)->count(15)->create();
    $profile = $profile->fresh();
    $post = $profile->lastPost()->first();
    $comment = Comment::factory()->for($profile)->for($post)->create(['body' => 'commentA']);
    Comment::factory()->for($post)->create(['body' => 'commentB']);
    Comment::factory()->for($post)->create(['body' => 'commentC']);


    $response = deleteJson(action([CommentController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'post' => $post->id,
        'comment' => $comment->id,
    ]));

    $response->assertNoContent();

    expect($profile->comments()->get())->toBeEmpty();
    expect($post->comments()->where('id', $comment->id)->get())->toBeEmpty();
    expect(Comment::query()->where('id', $comment->id)->get())->toBeEmpty();
});
