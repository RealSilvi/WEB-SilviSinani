<?php

use App\Enum\NewsType;
use App\Http\Controllers\Api\NewsController;
use App\Models\Comment;
use App\Models\News;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('can fetch news', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    News::factory()->for($profile)->create();
    News::factory()->for($profile)->create();
    $news = News::factory()->for($profile)->create(['created_at' => now()->addDay()]);

    $response = getJson(action([NewsController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'include' => [],
    ]));

    $response->assertOk();
    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', 3, fn (AssertableJson $json) => $json
            ->where('id', $news->id)
            ->where('fromNickname', $news->from_nickname)
            ->where('profileId', $profile->id)
            ->etc()
        )
        ->etc()
    );
});

it('can fetch news full', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->create();

    News::factory()->for($profile)->create();
    News::factory()->for($profile)->create();
    $news = News::factory()->for($profile)->create([
        'created_at' => now()->addDay(),
        'from_id' => $profileB->id,
        'from_type' => Profile::class,
        'type' => NewsType::FOLLOW_REQUEST,
    ]);

    $response = getJson(action([NewsController::class, 'index'], [
        'user' => $user->id,
        'profile' => $profile->id,
        'include' => [
            'profile',
            'from',
        ],
    ]));
    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', 3, fn (AssertableJson $json) => $json
            ->where('id', $news->id)
            ->where('fromId', $profileB->id)
            ->where('fromType', Profile::class)
            ->where('type', NewsType::FOLLOW_REQUEST->value)
            ->where('id', $news->id)
            ->has('profile', fn (AssertableJson $json) => $json
                ->where('id', $profile->id)
                ->etc())
            ->has('from', fn (AssertableJson $json) => $json
                ->where('id', $profileB->id)
                ->etc())
            ->etc()
        )
        ->etc()
    );
});

it('can store a follow request news ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $response = postJson(action([NewsController::class, 'store'], ['user' => $userB->id, 'profile' => $profileB->id]), [
        'fromId' => $profileB->id,
        'fromType' => Profile::class,
        'type' => NewsType::FOLLOW_REQUEST,
        'profileId' => $profile->id,
        'fromNickname' => $profileB->nickname,
    ]);
    $response->assertCreated();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', fn (AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('type', NewsType::FOLLOW_REQUEST->value)
            ->where('fromType', Profile::class)
            ->where('fromId', $profileB->id)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile)->news->not()->toBeNull();
    $news = $profile->news->first();
    expect($news)->type->toBe(NewsType::FOLLOW_REQUEST->value);
    expect($news)->from_id->toBe($profileB->id);
    expect($news)->from_type->toBe(Profile::class);

});

it('can store a post like news ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();
    $post = Post::factory()->for($profile)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $response = postJson(action([NewsController::class, 'store'], ['user' => $userB->id, 'profile' => $profileB->id]), [
        'fromId' => $post->id,
        'fromType' => Post::class,
        'fromNickname' => $profileB->nickname,
        'type' => NewsType::POST_LIKE,
        'profileId' => $profile->id,
    ]);

    $response->assertCreated();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', fn (AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('type', NewsType::POST_LIKE->value)
            ->where('fromType', Post::class)
            ->where('fromId', $post->id)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile)->news->not()->toBeNull();
    $news = $profile->news->first();
    expect($news)->type->toBe(NewsType::POST_LIKE->value);
    expect($news)->from_id->toBe($post->id);
    expect($news)->from_type->toBe(Post::class);

});

it('can store a comment like news ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $post = Post::factory()->for($profile)->create();
    $comment = Comment::factory()->for($profileB)->for($post)->create();
    $response = postJson(action([NewsController::class, 'store'], ['user' => $userB->id, 'profile' => $profileB->id]), [
        'fromId' => $post->id,
        'fromType' => Post::class,
        'fromNickname' => $profileB->nickname,
        'type' => NewsType::COMMENT_LIKE,
        'profileId' => $profile->id,
    ]);

    $response->assertCreated();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', fn (AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('type', NewsType::COMMENT_LIKE->value)
            ->where('fromType', Post::class)
            ->where('fromId', $post->id)
            ->where('fromNickname', $profileB->nickname)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile)->news->not()->toBeNull();
    $news = $profile->news->first();
    expect($news)->type->toBe(NewsType::COMMENT_LIKE->value);
    expect($news)->from_id->toBe($post->id);
    expect($news)->from_type->toBe(Post::class);

});

it('can store a comment news ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $userB = User::factory()->create();
    $profileB = Profile::factory()->for($userB)->create();
    $profileC = Profile::factory()->for($userB)->create();
    $profileD = Profile::factory()->for($userB)->create();

    $post = Post::factory()->for($profile)->create();
    $comment = Comment::factory()->for($profileB)->for($post)->create();
    $response = postJson(action([NewsController::class, 'store'], ['user' => $userB->id, 'profile' => $profileB->id]), [
        'fromId' => $post->id,
        'fromType' => Post::class,
        'fromNickname' => $profileB->nickname,
        'type' => NewsType::COMMENT,
        'profileId' => $profile->id,
    ]);

    $response->assertCreated();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', fn (AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('type', NewsType::COMMENT->value)
            ->where('fromType', Post::class)
            ->where('fromId', $post->id)
            ->where('fromNickname', $profileB->nickname)
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile)->news->not()->toBeNull();
    $news = $profile->news->first();
    expect($news)->type->toBe(NewsType::COMMENT->value);
    expect($news)->from_id->toBe($post->id);
    expect($news)->from_type->toBe(Post::class);
    expect($news)->from_nickname->toBe($profileB->nickname);

});

it('can seen correctly ', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    News::factory()->for($profile)->create();
    News::factory()->for($profile)->create();
    News::factory()->for($profile)->create();

    $response = postJson(action([NewsController::class, 'seeAll'], ['user' => $user->id, 'profile' => $profile->id]));
    $response->assertNoContent();

    $profile = $profile->fresh();

    $profile->news()->get()->each(fn (News $new) => expect($new->seen)->toBeTrue());
});
