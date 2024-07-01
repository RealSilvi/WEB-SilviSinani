<?php

declare(strict_types=1);

namespace App\Actions\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateCommentInput extends Data
{
    public function __construct(
        public string $body,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'body' => ['required', 'string'],
        ];
    }
}
