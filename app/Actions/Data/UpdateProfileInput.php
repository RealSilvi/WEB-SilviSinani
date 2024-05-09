<?php

declare(strict_types=1);

namespace App\Actions\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateProfileInput extends Data
{
    public function __construct(
        public ?string $nickname = null,
        public ?bool   $default = null,
        public ?string $dateOfBirth = null,
        public ?string $breed = null,
        public ?UploadedFile $mainImage = null,
        public ?UploadedFile $secondaryImage = null,
        public ?string $bio = null,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'nickname' => ['nullable', 'string', 'max:255'],
            'default' => ['nullable', 'bool'],
            'dateOfBirth' => ['nullable', 'date'],
            'breed' => ['nullable', 'string'],
            'mainImage' => ['nullable', 'image'],
            'secondaryImage' => ['nullable', 'image'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
