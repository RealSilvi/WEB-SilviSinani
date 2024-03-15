<?php

namespace App\Http\Resources;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AnonymousResourceCollection extends \Illuminate\Http\Resources\Json\AnonymousResourceCollection
{
    public function toResponseData(?Request $request = null): array
    {
        return Arr::get(
            $this
                ->toResponse($request ?? Container::getInstance()->make('request'))
                ->getData(true),
            'data'
        );
    }
}
