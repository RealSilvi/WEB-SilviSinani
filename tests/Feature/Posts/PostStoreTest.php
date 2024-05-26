<?php

use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;

it('can create a post', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $imageUrl = UploadedFile::fake()->image('post.jpg');

    $profileResponse = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'scott',
        'default' => true,
        'type' => ProfileType::DOG,
    ]);

    $profileResponse->assertCreated();

    $profile = Profile::query()->find($profileResponse->json('data.id'));

    $response = postJson(action([PostController::class, 'store'], ['user' => $user->id, 'profile' => $profile->id]), [
        'description' => 'Post description Test',
        'image' => $imageUrl,
    ]);
    $response->assertCreated();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('profileId', $profile->id)
            ->where('description', 'Post description Test')
            ->has('image')
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect($profile)
        ->not()->toBeNull()
        ->posts->not()->toBeNull();

    Storage::disk('public')->deleteDirectory('profiles/scott');

});
