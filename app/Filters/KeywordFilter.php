<?php

namespace App\Filters;
use Closure;

class KeywordFilter
{
    public function handle($query, Closure $next)
    {
        if (!request()->has('keyword')) {
            return $next($query);
        }

        $query->where('title', 'like', '%' . request('keyword') . '%')
            ->orWhere('content', 'like', '%' . request('keyword') . '%');

        return $next($query);
    }
}