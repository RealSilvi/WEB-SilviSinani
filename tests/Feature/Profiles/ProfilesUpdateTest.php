<?php

use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\ProfileController;
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
    $mainUrl =  UploadedFile::fake()->image(storage_path('/test/test.png'));
    $secondaryUrl =  UploadedFile::fake()->image(storage_path('/test/test.png'));

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profile->id)]), [
        'nickname' => 'Scott',
        'dateOfBirth' => $date,
        'breed' => ProfileBreedDog::GOLDEN_RETRIEVER->value,
        'mainImage' => $mainUrl,
        'secondaryImage' => $secondaryUrl,
        'bio' => 'Scott it is an awesome dog.',
    ]);

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', fn(AssertableJson $json) => $json
            ->has('type')
            ->where('id', $profile->id)
            ->where('nickname', 'Scott')
            ->where('userId', $user->id)
            ->where('dateOfBirth', $date)
            ->where('breed', ProfileBreedDog::GOLDEN_RETRIEVER->value)
            ->where('mainImage','profiles/'.$mainUrl->hashName())
            ->where('secondaryImage', 'backgrounds/'.$secondaryUrl->hashName())
            ->where('bio', 'Scott it is an awesome dog.')
            ->etc()
        )
    );

    $user = $user->fresh();
    $profile = $profile->fresh();

    expect($profile)
        ->user_id->toBe($user->id)
        ->nickname->toBe('Scott')
        ->date_of_birth->toBe($date)
        ->breed->toBe(ProfileBreedDog::GOLDEN_RETRIEVER->value)
        ->main_image->toBe('profiles/'.$mainUrl->hashName())
        ->secondary_image->toBe('backgrounds/'.$secondaryUrl->hashName())
        ->bio->toBe('Scott it is an awesome dog.')
        ->type->not()->toBeNull();

    Storage::disk('public')->delete($response->json('data.mainImage'));
    Storage::disk('public')->delete($response->json('data.secondaryImage'));
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
    $mainUrl =  UploadedFile::fake()->image(storage_path('/test/test.png'));
    $secondaryUrl =  UploadedFile::fake()->image(storage_path('/test/test.png'));

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileB->id)]), [
        'nickname' => $profileA->nickname,
        'dateOfBirth' => $date,
        'breed' => ProfileBreedDog::GOLDEN_RETRIEVER->value,
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

    $profileA = Profile::factory()->for($user)->create(['default'=>true]);
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
    expect($user->profiles()->where('default',true)->count())->toBe(1);
    expect($user->profiles()->where('default',true)->first()->toArray())->toBe($profileA->toArray());
});

it('can manage default updates, true to true', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default'=>true]);
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
    expect($user->profiles()->where('default',true)->count())->toBe(1);
    expect($user->profiles()->where('default',true)->first()->toArray())->toBe($profileA->toArray());
});

it('can manage default updates, false to true', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default'=>true]);
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
    expect($user->profiles()->where('default',true)->count())->toBe(1);
    expect($user->profiles()->where('default',true)->first()->toArray())->toBe($profileB->toArray());
});

it('can manage default updates, true to false', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default'=>true]);
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
    expect($user->profiles()->where('default',true)->count())->toBe(1);
    expect($user->profiles()->where('default',true)->first()->toArray())->toBe($profileB->toArray());
});

it('can not update default of last profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['default'=>true]);

    $response = patchJson(action([ProfileController::class, 'update'], ['user' => $user->id, 'profile' => ($profileA->id)]), [
        'default' => false
    ]);

    $response->assertMethodNotAllowed();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Cannot change the default status of your last profile');
});
