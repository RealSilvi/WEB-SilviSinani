<?php

namespace Database\Factories;

use App\Enum\ProfileType;
use App\Models\Profile;
use App\Models\User;
use App\Support\ImageGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nickname' => Str::slug(fake()->userName()),
            'bio' => fake()->realText,
            'main_image' => '/utilities/profileDefault.jpg',
            'secondary_image' => '/utilities/backgroundDefault.jpg',
            'date_of_birth' => fake()->date,
            'default' => false,
            'type' => Arr::random(ProfileType::cases()),
            'breed' => fake()->name,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Profile $profile) {
            if($profile->user_id == null){
                $profile->user_id=User::factory()->create()->id;
            }
        });
    }

    public function realImages(): Factory
    {
        return $this->state(function (array $attributes) {
            $type = $attributes['type'];
            $mainImageEndLocation = '/profiles' . '/' . $attributes['nickname'];
            $secondaryImageEndLocation = '/profiles' . '/' . $attributes['nickname'];
            return [
                'main_image' =>app(ImageGenerator::class)->generate($mainImageEndLocation, 'profile.jpg', [$type->value, 'green']),
                'secondary_image' => app(ImageGenerator::class)->generate($secondaryImageEndLocation, 'background.jpg', ['background', 'green']),
            ];
        });
    }

}
