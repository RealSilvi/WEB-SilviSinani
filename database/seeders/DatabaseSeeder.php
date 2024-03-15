<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $members = ChatMember::factory(100)->create();

        for ($i = 0; $i < 50; $i++) {
            $group = Chat::factory()->create();
            /**@var Collection $members */
            $members
                ->slice(rand(0, 40), rand(2, 10))
                ->each(function (ChatMember $sender) use ($group) {
                    $sender->chat()->associate($group)->save();
                    Message::factory()->for($sender, 'sender')->for($group, 'chat')->create();
                });

            $chat = Chat::factory()->create();
            /**@var Collection $members */
            $members
                ->slice(rand(51, 98), 2)
                ->each(function (ChatMember $sender) use ($chat) {
                    $sender->chat()->associate($chat)->save();
                    Message::factory()->for($sender, 'sender')->for($chat, 'chat')->create();
                });
        }

    }
}
