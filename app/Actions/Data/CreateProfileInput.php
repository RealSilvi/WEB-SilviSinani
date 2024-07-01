<?php

declare(strict_types=1);

namespace App\Actions\Data;

use App\Enum\ProfileType;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateProfileInput extends Data
{
    public function __construct(
        public string $nickname,
        public ProfileType $type,
        public ?bool $default = false,
        public ?string $dateOfBirth = null,
        public ?string $breed = null,
        public ?UploadedFile $mainImage = null,
        public ?UploadedFile $secondaryImage = null,
        public ?string $bio = null,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'nickname' => ['required', 'string', 'max:255'],
            'default' => ['required', 'bool'],
            'dateOfBirth' => ['nullable', 'date'],
            'type' => ['nullable', new Enum(ProfileType::class)],
            'breed' => ['nullable', 'string'],
            'mainImage' => ['nullable', 'image'],
            'secondaryImage' => ['nullable', 'image'],
            'bio' => ['nullable', 'string'],
        ];
    }
}
