<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Actions\Data\CreateProfileInput;
use App\Exceptions\NicknameAlreadyExistsException;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\FilesystemException;
use Nette\FileNotFoundException;
use Nette\Utils\Image;
use Throwable;

class StoreImageOrStoreDefaultImageAction
{
    /**
     * @throws FilesystemException
     */
    public static function execute(?UploadedFile $image, string $filename, string $profilePathDirectory, string $defaultImagePath): string
    {
        if (!Storage::disk('public')->exists($defaultImagePath)) {
            throw new FileNotFoundException('Default image file does not exist' . ' ' . $defaultImagePath);
        }

        if (!Storage::disk('public')->directoryExists($profilePathDirectory)) {
            Storage::disk('public')->createDirectory($profilePathDirectory);
        }

        if ($image) {
            $image->storeAs($profilePathDirectory, $filename, 'public');
        } else {
            Storage::disk('public')->copy($defaultImagePath, $profilePathDirectory . '/' . $filename);
        }

        return $profilePathDirectory . '/' . $filename;
    }
}
