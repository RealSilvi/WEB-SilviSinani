<?php

namespace App\Http\Resources;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @mixin JsonResource
 *
 * @method static AnonymousResourceCollection collection($resource)
 */
trait ResourceSerialization
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

    protected static function newCollection($resource): AnonymousResourceCollection
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}

