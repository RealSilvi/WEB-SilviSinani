<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageGenerator
{
    public function __construct(public $baseUrl = "https://source.unsplash.com/random")
    {
    }

    public function generate(string $endLocation, ?string $filename = null, ?array $filters = []): string
    {
        $filename = $filename ?? Str::slug(now()->toString()).'.jpg';
        $url = $this->baseUrl . (count($filters) != 0 ? '?' : '');
        foreach ($filters as $filter) {
            $url = $url . '%20' . $filter;
        }

        $contents = file_get_contents($url);
        $basePathToFile = $endLocation . '/' . $filename;

        Storage::disk('public')->put($basePathToFile, $contents);
        return $basePathToFile;
    }

}
