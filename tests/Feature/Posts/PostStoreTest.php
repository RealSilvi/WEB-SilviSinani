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

it('can create a full profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $date = fake()->date();
    $mainUrl = UploadedFile::fake()->image('profile.jpg');
    $secondaryUrl = UploadedFile::fake()->image('background.jpg');
    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'scott',
        'default' => true,
        'type' => ProfileType::DOG,
        'dateOfBirth' => $date,
        'breed' => ProfileBreedDog::GOLDEN_RETRIEVER,
        'mainImage' => $mainUrl,
        'secondaryImage' => $secondaryUrl,
        'bio' => 'Scott it is an awesome dog.',
    ]);

    $response->assertCreated();


    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('nickname', 'scott')
            ->where('default', true)
            ->where('userId', $user->id)
            ->where('type', ProfileType::DOG->value)
            ->where('dateOfBirth', $date)
            ->where('breed', ProfileBreedDog::GOLDEN_RETRIEVER->value)
            ->where('mainImage', 'profiles/scott/profile.jpg')
            ->where('secondaryImage', 'profiles/scott/background.jpg')
            ->where('bio', 'Scott it is an awesome dog.')
            ->etc()
        )
    );

    Storage::disk('public')->deleteDirectory('profiles/scott');
});

it('can not create a profile with the same nickname', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Profile::factory()->for($user)->create(['nickname' => 'scott']);
    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'scott',
        'type' => ProfileType::DOG,
    ]);

    $response->assertNotAcceptable();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Nickname already exists');
});

it('can not create a profile when already 4 existing', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Profile::factory()->for($user)->create(['nickname' => 'Buddy']);
    Profile::factory()->for($user)->create(['nickname' => 'Roxie']);
    Profile::factory()->for($user)->create(['nickname' => 'Charlie']);
    Profile::factory()->for($user)->create(['nickname' => 'Lily']);
    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'scott',
        'type' => ProfileType::DOG,
    ]);


    $response->assertMethodNotAllowed();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Already 4 profiles existing for this user. Delete one of them before storing a new one');
});

it('first profile is default', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->make(['nickname' => 'buddy']);
    $profileB = Profile::factory()->make(['nickname' => 'roxie']);
    $profileC = Profile::factory()->make(['nickname' => 'charlie']);

    postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), $profileA->toArray());
    postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), $profileB->toArray());
    postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), $profileC->toArray());

    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'Scott',
        'type' => ProfileType::DOG,
    ]);


    $response->assertCreated();

    $user = $user->fresh();
    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->nickname)->toBe($profileA->nickname);

    Storage::disk('public')->deleteDirectory('profiles/' . $profileA->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $profileB->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $profileC->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $response->json('data.nickname'));
});

it('changes default correctly', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->make(['nickname' => 'buddy']);
    $profileB = Profile::factory()->make(['nickname' => 'roxie']);
    $profileC = Profile::factory()->make(['nickname' => 'charlie', 'default' => true]);

    postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), $profileA->toArray());
    postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), $profileB->toArray());
    postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), $profileC->toArray());

    $response = postJson(action([ProfileController::class, 'store'], ['user' => $user->id]), [
        'nickname' => 'Scott',
        'type' => ProfileType::DOG,
    ]);
    $response->assertCreated();


    $user = $user->fresh();
    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->nickname)->toBe($profileC->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $profileA->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $profileB->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $profileC->nickname);
    Storage::disk('public')->deleteDirectory('profiles/' . $response->json('data.nickname'));
});

