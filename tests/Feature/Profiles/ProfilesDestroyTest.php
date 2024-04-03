<?php

use App\Enum\ProfileBreedDog;
use App\Enum\ProfileType;
use App\Http\Controllers\ProfileController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

it('can delete a profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['nickname' => 'Scott']);
    $profileB = Profile::factory()->for($user)->create(['nickname' => 'Buddy']);
    $profileC = Profile::factory()->for($user)->create(['nickname' => 'Roxie']);

    $response = deleteJson(action([ProfileController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profileA->id
    ]));

    $response->assertNoContent();

    $user = $user->fresh();
    $profileA = $profileA->fresh();

    expect($profileA)->toBeNull();
    expect($user->profiles()->get())->not()->toBeEmpty();
    expect($user->profiles()->where('id', $profileA)->get())->toBeEmpty();
    expect(Profile::query()->where('id', $profileA)->get())->toBeEmpty();
    expect(Profile::query()->where('user_id', $user->id)->get()->count())->toBe(2);
});

it('restore casual default profile when you are deleting a default profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['nickname' => 'Scott', 'default' => true]);
    $profileB = Profile::factory()->for($user)->create(['nickname' => 'Buddy']);
    $profileC = Profile::factory()->for($user)->create(['nickname' => 'Roxie']);

    $response = deleteJson(action([ProfileController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profileA->id
    ]));

    $response->assertNoContent();

    $user = $user->fresh();

    expect($user->profiles()->where('default', true)->count())->toBe(1);
    expect($user->profiles()->where('default', true)->first()->nickname)->not()->toBe($profileA->nickname);
});

it('can not delete last profile', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['nickname' => 'Scott']);

    $response = deleteJson(action([ProfileController::class, 'destroy'], [
        'user' => $user->id,
        'profile' => $profileA->id
    ]));

    $response->assertMethodNotAllowed();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Cannot delete your last profile. If you want you can delete the user.');
});

it('can not delete profile when does not match user profiles', function () {
    $user = User::factory()->create();
    $userA = User::factory()->create();
    Sanctum::actingAs($user);

    $profileA = Profile::factory()->for($user)->create(['nickname' => 'Scott']);

    $response = deleteJson(action([ProfileController::class, 'destroy'], [
        'user' => $userA->id,
        'profile' => ($profileA->id)
    ]));

    $response->assertNotAcceptable();

    expect($response->json())
        ->error->toBe(true)
        ->message->toBe('Profile does not match any of the user profiles.');
});
