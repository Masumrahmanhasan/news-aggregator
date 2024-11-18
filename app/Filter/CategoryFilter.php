<?php

namespace App\Filter;
use Closure;

class CategoryFilter
{
    public function handle($query, Closure $next)
    {
        if (!request()->has('category')) {
            return $next($query);
        }

        $query->where('category', request('category'));

        return $next($query);
    }
}