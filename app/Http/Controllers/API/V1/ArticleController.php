<?php

namespace App\Http\Controllers\API\V1;

use App\Filters\CategoryFilter;
use App\Filters\DateFilter;
use App\Filters\KeywordFilter;
use App\Filters\SourceFilter;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;

class ArticleController extends Controller
{
    use SendResponse;

    public function index(): JsonResponse
    {
        $articles = app(Pipeline::class)
            ->send(Article::query())
            ->through([
                KeywordFilter::class,
                DateFilter::class,
                CategoryFilter::class,
                SourceFilter::class,
            ])
            ->thenReturn()
            ->latest()
            ->paginate(20);

        return $this->success($articles, 'Articles retrieved successfully.');
    }

    public function show(Article $article): JsonResponse
    {
        return $this->success($article, 'Article retrieved successfully.');
    }
}
