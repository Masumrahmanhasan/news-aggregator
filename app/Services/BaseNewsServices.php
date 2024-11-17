<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\NewsSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function dd;

abstract class BaseNewsServices implements NewsSourceInterface
{
    protected function storeArticles(array $articles, string $category): void
    {
        $category = $this->storeArticleCategory($category);
        $data = [];
        foreach ($articles as $article) {
            if (!empty($article['content'])) {
                $data[] = [
                    'title' => $article['title'],
                    'source' => $article['source']['name'],
                    'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                    'content' => $article['content'],
                    'author' => $article['author'],
                ];
            }
        }

        // Use a transaction to ensure data integrity and improve performance
        DB::transaction(function () use ($data, $category) {
            Article::query()->upsert($data, ['title', 'source', 'published_at'], ['content', 'author']);
            $titles = collect($data)->pluck('title');
            $savedArticles = Article::query()
                ->whereIn('title', $titles)
                ->get();

            // Attach the category to all articles
            foreach ($savedArticles as $article) {
                $article->categories()->syncWithoutDetaching($category->id);
            }
        });
    }

    private function storeArticleCategory(string $category)
    {
        return Category::query()
            ->updateOrCreate(['name' => $category]);
    }
}
