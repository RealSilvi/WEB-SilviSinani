<?php

use App\Http\Controllers\Api\AccountController;
use App\Models\Account;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\getJson;

it('can fetch accounts', function () {

    $user= User::factory()->create();
    $accountA= Account::factory()->create();
    $accountB= Account::factory()->create();
    $accountC= Account::factory()->create();

    $user->accounts()->saveMany([$accountA,$accountB,$accountC]);
    User::factory()->count(10)->has(Account::factory()->count(rand(1,4)))->create();

    $response = getJson(action([AccountController::class, 'index'], [
        'user' => $user->id,
    ]));

    $response->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('data', 3, fn (AssertableJson $json) => $json
            ->where('userId', $user->id)
            ->where('id', $accountA->id)
            ->where('name', $accountA->name)
            ->where('slug', $accountA->slug)
            ->where('createdAt', $accountA->created_at->toJson())
            ->where('updatedAt', $accountA->updated_at->toJson())
            ->etc()
        )
        ->etc()
    );
});

