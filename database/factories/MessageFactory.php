<?php

namespace Database\Factories;

use App\Enums\MessageType;
use App\Models\Account;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => MessageType::TEXTUAL,
            'body' => fake()->sentence,
            'seen' => false,
            'deleted' => false,
            'chat_id' => Chat::factory(),
            'sender_id' => Account::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
