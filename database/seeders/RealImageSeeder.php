<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Profile;
use App\Support\ImageGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RealImageSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('profiles');

        foreach (Profile::all() as $profile) {
            $type = $profile->type;
            $mainImageEndLocation = '/profiles' . '/' . $profile->nickname;
            $secondaryImageEndLocation = '/profiles' . '/' . $profile->nickname;
            $profile->update([
                'main_image' => app(ImageGenerator::class)
                    ->generate(
                        endLocation: $mainImageEndLocation,
                        filename: 'profile.jpg',
                        filters: [$type->value, 'green']
                    ),
                'secondary_image' => app(ImageGenerator::class)
                    ->generate(
                        endLocation: $secondaryImageEndLocation,
                        filename: 'background.jpg',
                        filters: ['background', 'green']
                    ),
            ]);

            $profile->posts()->each(function (Post $post) use ($profile) {
                if (rand(0, 1)) {
                    return;
                }

                $postEndLocation = '/profiles' . '/' . $profile->nickname . '/posts';
                $post->update([
                    'image' => app(ImageGenerator::class)
                        ->generate(
                            endLocation: $postEndLocation,
                            filters: ['post', $profile->type->value,]
                        ),
                ]);
                $post->save();
            });

            $profile->save();
        }

    }
}
