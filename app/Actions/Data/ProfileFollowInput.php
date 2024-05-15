<?php

declare(strict_types=1);

namespace App\Actions\Data;

use App\Enum\ProfileType;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ProfileFollowInput extends Data
{
    public function __construct(
        public int $followerId
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'followerId' => ['required', 'int', 'max:255'],
        ];
    }
}
