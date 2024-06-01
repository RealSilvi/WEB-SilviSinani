<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Data\CreatePostInput;
use App\Exceptions\CannotCreateAnEmptyPostException;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use Throwable;

class CreatePostAction
{
    /**
     * @throws Throwable
     */
    public function execute(User $user, Profile $profile, CreatePostInput $input): Post
    {
        $this->validateInput($input);

        return DB::transaction(function () use ($user, $profile, $input): Post {
            return $this->createPost($user, $profile, $input);
        });
    }


    /**
     * Validate and create a newly registered profile.
     *
     * @param User $user
     * @param Profile $profile
     * @param CreatePostInput $input
     * @return Post
     */
    public function createPost(User $user, Profile $profile, CreatePostInput $input): Post
    {
        try {
            $image = $this->checkAndSaveImageOnStorage($profile, $input);
        } catch (FilesystemException|FileNotFoundException) {
            $image = null;
        }

        $post = new Post([
            'description' => $input->description,
            'image' => $image,
            'profile_id' => $profile->id,
        ]);

        $post->save();

        return $post;
    }

    /**
     * @throws CannotCreateAnEmptyPostException
     */
    protected function validateInput(CreatePostInput $input): void
    {
        if (!$input->image && !$input->description) {
            throw new CannotCreateAnEmptyPostException('Cannot create an empty post');
        }

    }

    /**
     * @throws FileNotFoundException
     * @throws FilesystemException
     */
    public function checkAndSaveImageOnStorage(Profile $profile, CreatePostInput $input): ?string
    {

        if (!$input->image) {
            return null;
        }

        $profilePathDirectory = '/profiles/' . $profile->nickname;
        $postPathDirectory = $profilePathDirectory . '/posts';

        if (!Storage::disk('public')->exists($profilePathDirectory)) {
            throw new FileNotFoundException('Profile storage not found' . ' ' . $profilePathDirectory);
        }

        if (!Storage::disk('public')->directoryExists($postPathDirectory)) {
            Storage::disk('public')->createDirectory($postPathDirectory);
        }

        return Storage::disk('public')->put($postPathDirectory, $input->image);
    }

}
