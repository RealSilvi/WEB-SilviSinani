<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password'
        ]);

        User::factory(100)
            ->has(
                Account::factory()
                    ->count(2)
            )
            ->create();

        $accounts = Account::query()->get();

        $chats = Chat::factory()->count(100)->create();

        foreach ($chats as $chat) {
            /**@var Chat $chat */
            $chat->members()->attach($accounts->random(rand(2, 10)));
        }

        for ($i = 0; $i < 500; $i++) {
            $chat = $chats->random(1)->first();
            $sender = $chat->members->random(1)->first();
            Message::factory()
                ->for($sender, 'sender')
                ->for($chat, 'chat')
                ->create();
        }

    }
}
