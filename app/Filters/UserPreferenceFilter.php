<?php

namespace App\Filters;
use Closure;

class UserPreferenceFilter
{
    public function handle($query, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->preferences) {
            $preferences = $user->preferences;

            $query->when($preferences->preferred_sources, function ($q) use ($preferences) {
                $q->whereIn('source', $preferences->preferred_sources);
            });

            $query->when($preferences->preferred_categories, function ($q) use ($preferences) {
                $q->whereIn('category', $preferences->preferred_categories);
            });

            $query->when($preferences->preferred_authors, function ($q) use ($preferences) {
                $q->whereIn('author', $preferences->preferred_authors);
            });
        }

        return $next($query);
    }
}