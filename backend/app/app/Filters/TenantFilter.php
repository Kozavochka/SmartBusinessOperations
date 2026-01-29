<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TenantFilter implements Filter
{
    /**
     * @param  mixed  $value
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        $query->where('name', 'ilike', "%{$value}%");
    }
}
