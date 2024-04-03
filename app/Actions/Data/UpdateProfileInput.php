<?php

declare(strict_types=1);

namespace App\Actions\Data;

use App\Enum\ProfileType;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateProfileInput extends Data
{
    public function __construct(
        public ?string $nickname = null,
        public ?bool   $default = false,
        public ?string $dateOfBirth = null,
        public ?string $breed = null,
        public ?string $mainImage = null,
        public ?string $secondaryImage = null,
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
            'mainImage' => ['nullable', 'url'],
            'secondaryImage' => ['nullable', 'url'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
