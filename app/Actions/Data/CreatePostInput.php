<?php

declare(strict_types=1);

namespace App\Actions\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreatePostInput extends Data
{
    public function __construct(
        public ?UploadedFile $image = null,
        public ?string $description = null,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'image' => ['nullable', 'image'],
            'description' => ['nullable', 'string'],
        ];
    }
}
