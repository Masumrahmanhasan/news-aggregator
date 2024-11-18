<?php

namespace App\Services;

use App\Models\Article;
use App\NewsSourceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NewsApiService implements NewsSourceInterface
{
    protected string $url;
    protected string $apiKey;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->url = config('services.news_api.base_url');
        $this->apiKey = config('services.news_api.api_key');
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    protected function sendRequest(string $endpoint, $params = []): array
    {
        $response = Http::withHeaders(['x-api-key' => $this->apiKey])
            ->baseUrl($this->url)->get($endpoint, $params);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch data from News API: ' . $response->body());
        }
        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function fetchArticles(): void
    {
        $response = $this->sendRequest('top-headlines', ['category' => 'Business']);

        $this->storeArticles($response['articles'], 'Business');
    }

    protected function storeArticles(array $articles, $category): void
    {
        $data = [];
        foreach ($articles as $article) {
            if (!empty($article['content'])) {
                $data[] = [
                    'title' => $article['title'],
                    'source' => $article['source']['name'],
                    'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                    'content' => $article['content'],
                    'author' => $article['author'],
                    'category' => $category,
                ];
            }
        }

        // Use a transaction to ensure data integrity and improve performance
        DB::transaction(function () use ($data, $category) {
            Article::query()->upsert($data, ['title', 'source', 'published_at'], ['content', 'author', 'category']);
        });
    }
}
