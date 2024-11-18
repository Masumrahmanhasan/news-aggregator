<?php

namespace App\Filters;
use Closure;

class DateFilter
{
    public function handle($query, Closure $next)
    {
        if (!request()->has('date')) {
            return $next($query);
        }

        $query->whereDate('published_at', request('date'));

        return $next($query);
    }
}