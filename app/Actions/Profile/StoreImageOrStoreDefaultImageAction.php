<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use Nette\FileNotFoundException;

class StoreImageOrStoreDefaultImageAction
{
    /**
     * @throws FilesystemException
     */
    public static function execute(?UploadedFile $image, string $filename, string $profilePathDirectory, string $defaultImagePath): string
    {

        if (! Storage::disk('public')->directoryExists($profilePathDirectory)) {
            Storage::disk('public')->createDirectory($profilePathDirectory);
        }

        if ($image) {
            $image->storeAs($profilePathDirectory, $filename, 'public');
        } else {
            if (! Storage::disk('public')->exists($defaultImagePath)) {
                throw new FileNotFoundException('Default image file does not exist'.' '.$defaultImagePath);
            }
            Storage::disk('public')->copy($defaultImagePath, $profilePathDirectory.'/'.$filename);
        }

        return $profilePathDirectory.'/'.$filename;
    }
}
