<?php

namespace App\Http\Controllers\API\V1;

use App\Filter\CategoryFilter;
use App\Filter\DateFilter;
use App\Filter\KeywordFilter;
use App\Filter\SourceFilter;
use App\Filter\UserPreferenceFilter;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\SendResponse;
use Illuminate\Pipeline\Pipeline;

class PersonalizedFeedController extends Controller
{
    use SendResponse;
    public function __invoke()
    {
        $articles = app(Pipeline::class)
            ->send(Article::query())
            ->through([
                UserPreferenceFilter::class, // Add this filter here
            ])
            ->thenReturn()
            ->latest()
            ->paginate(20);

        return $this->success($articles, 'Articles retrieved successfully.');
    }
}
