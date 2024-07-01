<?php

namespace App\Http\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class NewsTypeFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where('type', '!=', $value);
    }
}
