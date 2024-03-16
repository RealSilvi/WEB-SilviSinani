<?php

use App\Http\Controllers\Api\AccountController;
use App\Models\Account;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\getJson;

it('can fetch user accounts', function () {

    $user = User::factory()->create();
    $accountA = Account::factory()->create();
    $accountB = Account::factory()->create();
    $accountC = Account::factory()->create();

    $user->accounts()->saveMany([$accountA, $accountB, $accountC]);
    User::factory()->count(10)->has(Account::factory()->count(rand(1, 4)))->create();

    $response = getJson(action([AccountController::class, 'index'], [
        'user' => $user->id,
        'sort' => 'id'
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 3, fn(AssertableJson $json) => $json
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

it('can fetch user accounts with chats', function () {

    $user = User::factory()->create();
    $chatA = Chat::factory()->create();
    $chatB = Chat::factory()->create();
    $chatC = Chat::factory()->create();
    $accountA = Account::factory()
        ->for($user)
        ->hasAttached([$chatA, $chatB, $chatC])
        ->create();

    $user->accounts()->saveMany(
        Account::factory()
            ->count(2)
            ->has(
                Chat::factory()
                    ->count(10),
                'chats'
            )
            ->create());

    $response = getJson(action([AccountController::class, 'index'], [
        'user' => $user->id,
        'include' => ['chats'],
        'sort' => 'id'
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 3, fn(AssertableJson $json) => $json
            ->where('userId', $user->id)
            ->where('id', $accountA->id)
            ->where('name', $accountA->name)
            ->where('slug', $accountA->slug)
            ->where('createdAt', $accountA->created_at->toJson())
            ->where('updatedAt', $accountA->updated_at->toJson())
            ->has('chats', 3, fn(AssertableJson $json) => $json
                ->where('id', $chatA->id)
                ->where('name', $chatA->name)
                ->where('slug', $chatA->slug)
                ->where('createdAt', $chatA->created_at->toJson())
                ->where('updatedAt', $chatA->updated_at->toJson())
                ->etc()
            )
            ->etc()
        )
        ->etc()
    );
});

it('can fetch user accounts with chats with messages', function () {

    $user = User::factory()->create();
    $chatA = Chat::factory()->create();
    $chatB = Chat::factory()->create();
    $chatC = Chat::factory()->create();

    $accountA = Account::factory()
        ->for($user)
        ->hasAttached([$chatA, $chatB, $chatC])
        ->create();

    $messageA = Message::factory()->for($chatA)->for($accountA, 'sender')->create();
    Message::factory()->count(99)->for($chatA)->for(User::factory(), 'sender')->create();

    $user->accounts()->saveMany(
        Account::factory()
            ->count(2)
            ->has(
                Chat::factory()
                    ->count(10),
                'chats'
            )
            ->create());

    $response = getJson(action([AccountController::class, 'index'], [
        'user' => $user->id,
        'include' => ['chats.messages'],
        'sort' => 'id'
    ]));

    $response->assertOk();

    $response->assertJson(fn(AssertableJson $json) => $json
        ->has('data', 3, fn(AssertableJson $json) => $json
            ->where('userId', $user->id)
            ->where('id', $accountA->id)
            ->where('name', $accountA->name)
            ->where('slug', $accountA->slug)
            ->where('createdAt', $accountA->created_at->toJson())
            ->where('updatedAt', $accountA->updated_at->toJson())
            ->has('chats', 3, fn(AssertableJson $json) => $json
                ->where('id', $chatA->id)
                ->where('name', $chatA->name)
                ->where('slug', $chatA->slug)
                ->where('createdAt', $chatA->created_at->toJson())
                ->where('updatedAt', $chatA->updated_at->toJson())
                ->has('messages', 100, fn(AssertableJson $json) => $json
                    ->where('id', $messageA->id)
                    ->where('type', $messageA->type->value)
                    ->where('body', $messageA->body)
                    ->where('seen', $messageA->seen)
                    ->where('deleted', $messageA->deleted)
                    ->where('chatId', $messageA->chat_id)
                    ->where('senderId', $messageA->sender_id)
                    ->where('createdAt', $messageA->created_at->toJson())
                    ->where('updatedAt', $messageA->updated_at->toJson())
                    ->etc()
                )
                ->etc()
            )
            ->etc()
        )
        ->etc()
    );
});
