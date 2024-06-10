<?php

declare(strict_types=1);

namespace App\Actions\Data;

use App\Enum\NewsType;
use App\Enum\ProfileType;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateNewsInput extends Data
{
    public function __construct(
        public int      $fromId,
        public string   $fromType,
        public int      $profileId,
        public NewsType $type,
        public ?string  $title = null,
        public ?string  $body = null,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'title' => ['string'],
            'body' => ['string'],
            'profileId' => ['required', 'int'],
            'type' => ['required', new Enum(NewsType::class)],
        ];
    }
}
