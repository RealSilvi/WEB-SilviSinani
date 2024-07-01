<?php

use App\Enum\ProfileBreedDog;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\patchJson;

it('can update a profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create();

    $date = fake()->date();
    $mainUrl = UploadedFile::fake()->image(storage_path('/test/test.png'));
    $secondaryUrl = UploadedFile::fake()->image(storage_path('/test/test.png'));

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profile->id)]), [
        'nickname' => 'Scott',
        'dateOfBirth' => $date,
        'breed' => 'golden retriver',
        'mainImage' => $mainUrl,
        'secondaryImage' => $secondaryUrl,
        'bio' => 'Scott it is an awesome dog.',
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->has('type')
            ->where('id', $profile->id)
            ->where('nickname', 'scott')
            ->where('userId', $user->id)
            ->where('dateOfBirth', $date)
            ->where('breed', 'golden retriver')
            ->where('mainImage', '/profiles/scott/profile.jpg')
            ->where('secondaryImage', '/profiles/scott/background.jpg')
            ->where('bio', 'Scott it is an awesome dog.')
            ->etc()
        )
    );

    $user = $user->fresh();
    $profile = $profile->fresh();

    expect($profile)
        ->user_id->toBe($user->id)
        ->nickname->toBe('scott')
        ->date_of_birth->toBe($date)
        ->breed->toBe('golden retriver')
        ->main_image->toBe('/profiles/scott/profile.jpg')
        ->secondary_image->toBe('/profiles/scott/background.jpg')
        ->bio->toBe('Scott it is an awesome dog.')
        ->type->not()->toBeNull();

    Storage::disk('public')->deleteDirectory('profiles/' . $response->json('data.nickname'));
});

it('can not update profile when does not match user profiles', function () {
    $user = User::factory()->create();
    $userA = User::factory()->create();
    Sanctum::actingAs($user);

    $profile = Profile::factory()->for($user)->create(['nickname' => 'Scott']);

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $userA->id, 'profile' => ($profile->id)]), [
        'bio' => 'Scott it is an awesome dog.',
    ]);

    $response->assertNotAcceptable();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Profile does not match any of the user profiles.');
});

it('can not update a profile with the a nickname that already exists', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create();
    $profileB = Profile::factory()->for($user)->create();

    $date = fake()->date();
    $mainUrl = UploadedFile::fake()->image(storage_path('/test/test.png'));
    $secondaryUrl = UploadedFile::fake()->image(storage_path('/test/test.png'));

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileB->id)]), [
        'nickname' => $profileA->nickname,
        'dateOfBirth' => $date,
        'breed' => 'golden retriver',
        'mainImage' => $mainUrl,
        'secondaryImage' => $secondaryUrl,
        'bio' => 'Scott it is an awesome dog.',
    ]);


    $response->assertNotAcceptable();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Nickname already exists');
});

it('can manage default updates, false to false', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default' => true]);
    $profileB = Profile::factory()->for($user)->create();

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileB->id)]), [
        'default' => false
    ]);


    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('default', false)
            ->etc()
        )
    );

    $user = $user->fresh();
    $profileA = $profileA->fresh();
    $profileB = $profileB->fresh();

    expect($profileA)->default->toBeTrue();
    expect($profileB)->default->toBeFalse();
    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->toArray())->toBe($profileA->toArray());
});

it('can manage default updates, true to true', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default' => true]);
    $profileB = Profile::factory()->for($user)->create();

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileA->id)]), [
        'default' => true
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('default', true)
            ->etc()
        )
    );

    $user = $user->fresh();
    $profileA = $profileA->fresh();
    $profileB = $profileB->fresh();

    expect($profileA)->default->toBeTrue();
    expect($profileB)->default->toBeFalse();
    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->toArray())->toBe($profileA->toArray());
});

it('can manage default updates, false to true', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default' => true]);
    $profileB = Profile::factory()->for($user)->create();

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileB->id)]), [
        'default' => true
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('default', true)
            ->etc()
        )
    );

    $user = $user->fresh();
    $profileA = $profileA->fresh();
    $profileB = $profileB->fresh();

    expect($profileA)->default->toBeFalse();
    expect($profileB)->default->toBeTrue();
    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->toArray())->toBe($profileB->toArray());
});

it('can manage default updates, true to false', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default' => true]);
    $profileB = Profile::factory()->for($user)->create();

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileA->id)]), [
        'default' => false
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('default', false)
            ->etc()
        )
    );

    $user = $user->fresh();
    $profileA = $profileA->fresh();
    $profileB = $profileB->fresh();

    expect($profileA)->default->toBeFalse();
    expect($profileB)->default->toBeTrue();
    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->toArray())->toBe($profileB->toArray());
});

it('can not update default of last profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default' => true]);

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileA->id)]), [
        'default' => false
    ]);

    $response->assertMethodNotAllowed();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Cannot change the default status of your last profile');
});

it('can update storage paths', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    /** @var Profile $profile */
//    $profile = Profile::factory()->for($user)->realImages()->create();
    $profile = Profile::factory()->for($user)->create();
    $directoryUrl = 'profiles/' . $profile->nickname;
    $mainUrl = $profile->main_image;
    $secondaryUrl = $profile->secondary_image;

//    expect(Storage::disk('public')->directoryExists($directoryUrl))->toBeTrue();
    expect(Storage::disk('public')->exists($mainUrl))->toBeTrue();
    expect(Storage::disk('public')->exists($secondaryUrl))->toBeTrue();
    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profile->id)]), [
        'nickname' => 'Scott',
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->where('nickname', 'scott')
//            ->where('mainImage', '/profiles/scott/profile.jpg')
//            ->where('secondaryImage', '/profiles/scott/background.jpg')
            ->etc()
        )
    );

    $profile = $profile->fresh();
    expect(Storage::disk('public')->directoryExists($directoryUrl))->toBeFalse();
//    expect(Storage::disk('public')->exists($mainUrl))->toBeFalse();
//    expect(Storage::disk('public')->exists($secondaryUrl))->toBeFalse();
//    expect(Storage::disk('public')->directoryExists('profiles/' . $profile->nickname))->toBeTrue();
    expect(Storage::disk('public')->exists($profile->main_image))->toBeTrue();
    expect(Storage::disk('public')->exists($profile->secondary_image))->toBeTrue();

    Storage::disk('public')->deleteDirectory('profiles/' . $response->json('data.nickname'));
});
