<?php

declare(strict_types=1);

namespace App\Actions\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateProfileInput extends Data
{
    public function __construct(
        public string  $userId,
        public string  $nickname,
        public string  $dateOfBirth,
        public ?string $bio = null,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'userId' => ['nullable'],
            'nickname' => ['required', 'string', 'max:255'],
            'dateOfBirth' => ['nullable', 'date'],
            'bio' => ['nullable', 'string', 'max:255'],
        ];
    }
}
