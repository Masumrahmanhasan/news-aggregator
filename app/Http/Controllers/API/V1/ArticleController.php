<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    use SendResponse;

    public function index(): JsonResponse
    {
        $articles = Article::query()->latest()->paginate(20);
        return $this->success($articles, 'Articles retrieved successfully.');
    }

    public function show(Article $article): JsonResponse
    {
        $article->load('categories');
        return $this->success($article, 'Article retrieved successfully.');
    }
}
