<?php

namespace App\Filter;
use Closure;
class SourceFilter
{
    public function handle($query, Closure $next)
    {
        if (!request()->has('source')) {
            return $next($query);
        }

        $query->where('source', request('source'));

        return $next($query);
    }
}