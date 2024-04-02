<?php

declare(strict_types=1);

namespace App\Actions\Data;

use App\Enum\ProfileType;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateProfileInput extends Data
{
    public function __construct(
        public string      $nickname,
        public bool        $default,
        public ProfileType $type,
        public ?string     $dateOfBirth = null,
        public ?string     $breed = null,
        public ?string     $mainImage = null,
        public ?string     $secondaryImage = null,
        public ?string     $bio = null,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'nickname' => ['required', 'string', 'max:255'],
            'default' => ['required', 'bool'],
            'dateOfBirth' => ['nullable', 'date'],
            'type' => ['nullable', new Enum(ProfileType::class)],
            'breed' => ['nullable', 'string'],
            'mainImage' => ['nullable', 'url'],
            'secondaryImage' => ['nullable', 'url'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
